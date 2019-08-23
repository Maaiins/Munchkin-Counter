/**
 * index
 *
 * This file is part of the Munchkin project.
 *
 * @author     Lauser, Nicolai <munchkin-counter@lauser.info>
 * @copyright  2019 Lauser, Nicolai
 * @version    $Id$
 */
/*
 * ------------------------------
 * Style Dependencies
 * ------------------------------
 */
import '../css/index.scss';


/*
 * ------------------------------
 * JS Dependencies
 * ------------------------------
 */
import $ from 'jquery/dist/jquery.slim';
import 'bootstrap/js/dist/collapse';

import Vue from 'vue/dist/vue.esm';
import VueCookies from 'vue-cookies'
import axios from 'axios';


/*
 * ------------------------------
 * Application code
 * ------------------------------
 */
// Vue
Vue.use(VueCookies);

var app = new Vue({
    el: '#content',
    data: {
        username: '',
        pending: false,
    },
    methods: {
        submitHandler() {
            if (this.username.length > 0 && !this.pending) {
                this.pending = true;

                this.$cookies.set('username', this.username, '10y', null, window.location.hostname, true);

                const formData = new FormData();
                formData.append('username', this.username);

                axios.post('/api/player', formData).then(() => {
                    window.location.href = '/manage';
                });
            }
        }
    },
    beforeMount() {
        if (this.$cookies.isKey('username')) {
            this.username = this.$cookies.get('username');
        }
    },
    mounted() {
        const that = this;

        $(document).ready(() => {
            if (!that.username) {
                $('#usernameInput').addClass('show');
            }
        });
    }
});