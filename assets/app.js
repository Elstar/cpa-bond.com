/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application


import './bootstrap';
import { Tab, Dropdown, Tooltip, Modal} from 'bootstrap';
import 'select2'
import 'daterangepicker'

require('js.sortable/js/sortable.min')
require('./js/bootstrap_file_field')
require('./js/tiny.js')
require('./js/script.js')
require('./js/select2.js')

