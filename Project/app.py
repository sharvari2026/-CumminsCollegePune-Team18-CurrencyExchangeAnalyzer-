from flask import Flask, render_template, request
import requests

app = Flask(__name__)

API_URL = "https://api.exchangerate-api.com/v4/latest/"

def fetch_exchange_rate(base_currency):
    response = requests.get(f"{API_URL}{base_currency}")
    data = response.json()
    return data.get("rates", {})

def calculate_basket_value(base_currency, basket, weights):
    rates = fetch_exchange_rate(base_currency)
    basket_value = 0
    for currency, weight in zip(basket, weights):
        if currency in rates:
            basket_value += rates[currency] * weight
    return basket_value

@app.route('/', methods=['GET', 'POST'])
def index():
    if request.method == 'POST':
        base_currency = request.form.get("base_currency")
        usd_weight = float(request.form.get("usd_weight"))
        eur_weight = float(request.form.get("eur_weight"))
        jpy_weight = float(request.form.get("jpy_weight"))
        start_date = request.form.get("start_date")
        end_date = request.form.get("end_date")
        
        # Example basket and weights
        basket = ['USD', 'EUR', 'JPY']
        weights = [usd_weight, eur_weight, jpy_weight]
        
        # Calculating basket value (for simplicity, using current rates)
        basket_value = calculate_basket_value(base_currency, basket, weights)
        
        # Fake data for historical values and dates (replace with actual historical data)
        historical_values = [basket_value * i for i in range(1, 6)]  # Fake trend data
        dates = ['2022-01', '2022-02', '2022-03', '2022-04', '2022-05']  # Fake dates
        
        # Return to the template with calculated values and historical data
        return render_template('index.html', 
                               basket_value=basket_value, 
                               base_currency=base_currency,
                               historical_values=historical_values, 
                               dates=dates)
    
    return render_template('index.html')

if __name__ == '__main__':
    app.run(debug=True)
