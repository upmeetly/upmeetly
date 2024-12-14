import axios from 'axios';
import 'flowbite';
import './utils/darkmode-switcher.js';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
