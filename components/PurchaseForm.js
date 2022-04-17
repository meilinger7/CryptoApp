app.component('purchase-form', {
    props: {},
    template:
    /*html*/
        `
        <div class="flex-child kaufen">
        <form class="review-form" v-on:submit.prevent="submitForm">

            <h2>Cryptowährung kaufen</h2>
            <br>
            <p>Cryptowährung:</p>
            <select v-model="currency" name="currency" id="input">
                <option v-for='(value, name) in info' v-bind:value="name">{{ name }} {{value.EUR}}€ </option>
            </select>
            <p>Menge:</p>
            <input v-model.number="amount" placeholder="0.00" id="input">
            <p >Wert: {{sum}}€</p>
            <br>
            <input class="button" type="submit" value="Kaufen" id="button">  
            <br>
            </form>

        </div>

        `,
    data() {
        return {
            info: null,
            amount: 1,
            currency: null,
            form: {
                date: '',
                currency: '',
                amount: '',
                price: ''
            },
            postRes: null
        }
    },
    mounted() {
        axios.get('https://api.bitpanda.com/v1/ticker')
            .then(response => (this.info = response.data))
    },
    methods: {
        submitForm() {
            date = new Date();
            this.form.date = date.toISOString().slice(0, 19).replace('T', ' ');
            this.form.currency = this.currency
            this.form.amount = this.amount
            this.form.price = this.info[this.currency].EUR * this.amount

            console.log(this.form)

            axios.post('http://localhost/POS1_NEUN/CryptoApp/server/api.php?r=purchase', this.form)
                .then((response) => {
                    //Perform Success Action
                    this.postRes = response.data
                });
            location.reload()
        }
    },
    computed: {
        sum() {
            if (this.currency === null) {
                return "0.00"
            }
            // this.info.forEach((value, index) => {
            //     console.log(index);
            // });

            return this.amount * this.info[this.currency].EUR
        }
    }
})