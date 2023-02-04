import {VMoney} from 'v-money'

export default {
    data () {
        return {
            price: 0,
            money: {
                decimal: '.',
                thousands: ',',
                prefix: '',
                suffix: '',
                precision: 0,
                masked: false /* doesn't work with directive */
            }
        }
    },
    directives: {money: VMoney},
    filters: {
        formatMoney: function (num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
        },
        formatDate: function (date) {
            date = new Date(date);
            let day = date.getDate();
            let month = date.getMonth() + 1;
            return [
                day < 10 ? '0' + day : day,
                (month) < 10 ? '0' + month : month,
                date.getFullYear()
            ].join('-');
        }
    },
    methods: {
        formatMoney: function (num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
        },
    },
  };