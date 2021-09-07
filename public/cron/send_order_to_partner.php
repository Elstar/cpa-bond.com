<?php

ini_set("display_errors", "1");
error_reporting(E_ALL);
include_once __DIR__ . '/../config.php';
include_once __DIR__ . '/../classes/class.pdoHelper.php';
include_once __DIR__ . '/../classes/class.settings.php';

$payTypes = pdoHelper::getInstance()->selectRows("SELECT * FROM pay_types");
$payTypes = settings::get_array_by_key($payTypes, 'id');

$currencies = pdoHelper::getInstance()->selectRows("SELECT * FROM currency");
$currencies = settings::get_array_by_key($currencies, 'id');

$needSendLeads = pdoHelper::getInstance()->selectRows("SELECT * FROM `lead` WHERE gateway_status=0");

if (!empty($needSendLeads)) {
    foreach ($needSendLeads as $needSendLead) {
        $stream = pdoHelper::getInstance()->selectRow("SELECT * FROM stream WHERE id=?", [$needSendLead['stream_id']]);
        $offer = pdoHelper::getInstance()->selectRow("SELECT * FROM offer WHERE id=?", [$needSendLead['offer_id']]);
        $partner = pdoHelper::getInstance()->selectRow("SELECT * FROM partners WHERE id=?", [$offer['partner_id']]);

        $partnerAdditionalParams = pdoHelper::getInstance()->selectRows("SELECT * FROM partner_additional_params WHERE partner_id=?",
            [$offer['partner_id']]);

        $needSendLead['stream'] = $stream;
        $data = [];

        if ($partner["id"] == 1) {
            $needSendLead['ip'] = settings::varbinaryToIp($needSendLead['ip']);

            $sum = $offer['sum'];
            if (!is_null($needSendLead['sum'])) {
                $sum = $needSendLead['sum'];
            }
            $data = [
                'order_id' => (int)$needSendLead['id'],
                'name' => (string)$needSendLead['first_name'],
                'phone' => (string)$needSendLead['phone'],
                'ip' => (string)$needSendLead['ip'],
                'ua' => (string)$needSendLead['ua'],
                'order_source' => (string)$needSendLead['referer'],
                'uid' => (string) "bond_00" . $stream['user_id'],
                'items' => [
                    [
                        'item_id' => $offer['id'],
                        'price' => $sum,
                        'quantity' => 1,
                        'type' => 0
                    ]
                ]
            ];
            foreach ($partnerAdditionalParams as $partnerAdditionalParam) {
                $data[$partnerAdditionalParam['value_name']] = $partnerAdditionalParam['value'];
            }
        }
        if (!empty($data)) {
            if ($partner['data_format'] == 'xml') {
                $xml_data = new SimpleXMLElement('<?xml version="1.0"?><order></order>');
                settings::array_to_xml($data, $xml_data);
                $send_data = $xml_data->asXML();
            }
        }
        if ($partner["id"] == 1) {
            $send_data = [
                'xml' => $send_data,
                'pass' => '39a8aed0be50710a7a6016089fbcefa0'
            ];
            $result = settings::sendCurl($partner['http_server_send'], $send_data, 1);

            $arrObjData = simplexml_load_string($result);
            $arrObjData = settings::objectsIntoArray($arrObjData);
            $postbackLink = '';
            if (
                (empty($arrObjData["errors"]) && !empty($arrObjData["item"]))
                || ($arrObjData["errors"][0]["error"]["error_code"] == 103)
                || ($arrObjData["errors"][0]["error"]["error_code"] == 111)
                || ($arrObjData["errors"]["error"]["error_text"] == "Order already exists")
            ) {
                //Заказ успешно передан партнеру
                pdoHelper::getInstance()->update("UPDATE `lead` SET gateway_status=1, response_from_partner=? WHERE id=?", [$result, $needSendLead['id']]);

                $money = $stream['sum'];
                if ($payTypes[$offer['pay_type_id']] == 'CPB') {
                    $money = $arrObjData["item"]['money'];
                }
                $needSendLead['money'] = $money;
                $needSendLead['currency'] = $currencies[$offer['currency_id']];

                //проверяем есть ли ссылка для отправки постбека успешного заказа
                if ($stream['postback_create']) {
                    $postbackLink = settings::setPostBackLink($stream['postback_create'], $needSendLead);
                }
            } else {
                //Ошибка передачи заказа партнеру
                pdoHelper::getInstance()->update("UPDATE `lead` SET gateway_status=1, response_from_partner=? WHERE id=?", [$result, $needSendLead['id']]);
                if ($stream['postback_trash']) {
                    $postbackLink = settings::setPostBackLink($stream['postback_trash'], $needSendLead);
                }
            }
            if ($postbackLink)
                settings::sendCurl($postbackLink);
        }

    }
}
