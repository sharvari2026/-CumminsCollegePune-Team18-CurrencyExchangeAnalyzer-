const express = require('express');
const axios = require('axios');
const bodyParser = require('body-parser');

const app = express();
const PORT = 3000;
const API_URL = "https://api.exchangerate-api.com/v4/latest/";

app.set('view engine', 'ejs'); // Use EJS for rendering HTML
app.use(bodyParser.urlencoded({ extended: true }));

// Function to fetch exchange rates
async function fetchExchangeRate(baseCurrency) {
    const response = await axios.get(`${API_URL}${baseCurrency}`);
    return response.data.rates;
}

// Function to calculate basket value
async function calculateBasketValue(baseCurrency, basket, weights) {
    const rates = await fetchExchangeRate(baseCurrency);
    let basketValue = 0;
    for (let i = 0; i < basket.length; i++) {
        if (rates[basket[i]]) {
            basketValue += rates[basket[i]] * weights[i];
        }
    }
    return basketValue;
}

// Define the index route
app.get('/', (req, res) => {
    res.render('index', { basket_value: null, base_currency: null, historical_values: [], dates: [] });
});

app.post('/', async (req, res) => {
    const baseCurrency = req.body.base_currency;
    const usdWeight = parseFloat(req.body.usd_weight);
    const eurWeight = parseFloat(req.body.eur_weight);
    const jpyWeight = parseFloat(req.body.jpy_weight);

    // Example basket and weights
    const basket = ['USD', 'EUR', 'JPY'];
    const weights = [usdWeight, eurWeight, jpyWeight];

    // Calculate basket value
    const basketValue = await calculateBasketValue(baseCurrency, basket, weights);

    // Fake data for historical values and dates
    const historicalValues = Array.from({ length: 5 }, (_, i) => basketValue * (i + 1));
    const dates = ['2022-01', '2022-02', '2022-03', '2022-04', '2022-05'];

    // Return to the template with calculated values and historical data
    res.render('index', { 
        basket_value: basketValue, 
        base_currency: baseCurrency,
        historical_values: historicalValues, 
        dates: dates 
    });
});

// Start the server
app.listen(PORT, () => {
    console.log(`Server is running on http://localhost:${PORT}`);
});
