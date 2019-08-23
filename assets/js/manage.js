/**
 * manage
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
import '../css/manage.scss';


/*
 * ------------------------------
 * JS Dependencies
 * ------------------------------
 */
import $ from 'jquery/dist/jquery.slim';
import 'bootstrap/js/dist/modal';

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
        giftLevel: 1,
        player: {
            username: '',
            gender: 'male',
            level: 1,
            equipment: 0,
            bonus: 0,
        },
        players: null,
        fight: {
            player: '',
            bonus: 0,
        },
    },
    computed: {
        strongness() {
            return this.player.level + this.player.equipment + this.player.bonus;
        },
        fightStrongness() {
            const that = this;

            if (!that.players || !that.fight.player) {
                return that.fight.bonus + that.player.level + that.player.equipment + that.player.bonus;
            }

            const fighter = that.players.filter(player => {
                return player.username === that.fight.player
            })[0];

            if (!fighter) {
                return that.fight.bonus + that.player.level + that.player.equipment + that.player.bonus;
            }

            return that.fight.bonus + that.player.level + that.player.equipment + that.player.bonus + fighter.level + fighter.equipment + fighter.bonus;
        },
        otherPlayers() {
            const that = this;

            if (!that.players) {
                return [];
            }

            return that.players.filter(player => (player.username !== that.player.username));
        },
        sortedPlayers() {
            if (!this.players) {
                return [];
            }

            this.giftLevel = this.players.reduce((prev, curr) => {
                return prev.level < curr.level ? prev.level : curr.level;
            });

            return this.players.sort((a, b) => {
                return b.level - a.level
            });
        }
    },
    methods: {
        deadHandler() {
            this.player.equipment = 0;
            this.player.bonus = 0;

            this.updatePlayer();
        },
        genderHandler() {
            this.player.gender = (this.player.gender === 'male' ? 'female' : 'male');

            this.updatePlayer();
        },
        fightTogetherHandler() {
            console.log('fightTogetherHandler');

            this.updatePlayer();
        },
        fightHandler() {
            this.player.bonus = 0;

            this.updatePlayer();
        },
        levelDownHandler() {
            if (this.player.level > 1) {
                this.player.level--;

                this.updatePlayer();
            }
        },
        levelUpHandler() {
            this.player.level++;

            this.updatePlayer();
        },
        equipmentDownHandler() {
            this.player.equipment--;

            this.updatePlayer();
        },
        equipmentUpHandler() {
            this.player.equipment++;

            this.updatePlayer();
        },
        bonusDownHandler() {
            this.player.bonus--;

            this.updatePlayer();
        },
        bonusUpHandler() {
            this.player.bonus++;

            this.updatePlayer();
        },
        fightBonusDownHandler() {
            this.fight.bonus--;
        },
        fightBonusUpHandler() {
            this.fight.bonus++;
        },
        newGameHandler() {
            axios.get('/api/game/new').then(() => {
                window.location.href = '/';
            });
        },
        resizeCharacterImg() {
            const maxHeight = $(window).height();
            const characterActionsHeight = $('.character-actions').outerHeight(true);
            const characterPropertiesHeight = $('.character-properties').outerHeight(true);

            $('.character-img').height((maxHeight - characterPropertiesHeight - characterActionsHeight));
        },
        updatePlayer() {
            const that = this;

            const formData = new FormData();
            formData.append('username', that.player.username);
            formData.append('gender', that.player.gender);
            formData.append('level', that.player.level);
            formData.append('equipment', that.player.equipment);
            formData.append('bonus', that.player.bonus);

            axios.post('/api/player/update', formData).then(response => {
                that.players = response.data;
            });
        },
        getPlayers() {
            const that = this;

            axios.get('/api/players').then(response => {
                that.players = response.data;
                that.player = that.players.filter(player => (player.username === that.$cookies.get('username')))[0];
            });
        },
    },
    beforeMount() {
        if (this.$cookies.isKey('username')) {
            this.player.username = this.$cookies.get('username');
        } else {
            window.location.href = '/';
        }

        this.getPlayers()
    },
    mounted() {
        const that = this;

        that.$nextTick(function () {
            window.setInterval(() => {
                that.getPlayers();
            },10000);
        });

        $(document).ready(() => {
            that.resizeCharacterImg();

            $(window).resize(() => {
                that.resizeCharacterImg();
            });
        });
    }
});