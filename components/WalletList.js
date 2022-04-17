app.component('wallet-list', {
    props: {},
    template:
    /*html*/
        `
        <div class="flex-child wallet">
            <h2>Wallet {{sum}}€</h2>
            <ul id="example-1">
                <li v-for="item in info" :key="item.message">
                    <p>{{ item.amount }} {{ item.currency }} {{item.price}}€</p>
                </li>
            </ul>
        </div>
    `,
    data() {
        return {
            info: null
        }
    },
    mounted() {
        axios
            .get('http://localhost/POS1_NEUN/CryptoApp/server/api.php?r=purchase')
            .then(response => (this.info = response.data))
    },
    methods: {

    },
    computed: {
        sum() {
            if (this.info == null) {
                return
            }
            sum = 0
            this.info.forEach((value, index) => {
                sum += value.price
            });
            return sum
        }
    }
})