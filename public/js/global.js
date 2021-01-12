var locale = 'es';
var options = {minimumFractionDigits: 2, maximumFractionDigits: 2};
var formatter = new Intl.NumberFormat(locale, options);
var coinClient = 1;
var rateToday=0;

function showPrice(price, rate, coin, coinClient){
  rateToday = rate;
  if (price == "FREE")
      return "GRATIS";
  else if (coinClient == 0)
      return "$ "+ formatter.format(exchangeRate(price, rate, coin, coinClient));
  else
      return "Bs "+formatter.format(exchangeRate(price, rate, coin, coinClient));
}

function exchangeRate(price, rate, coin, coinClient){

  if(price == "FREE")
    price = 0;

  _coinClient = coinClient;
  _rate = rate;

  var result;

  if(coin == 0 && coinClient == 1)
    result = (parseFloat(price) * rate);
  else if(coin == 1 && coinClient == 0)
    result = (parseFloat(price) / rate);
  else
    result = (parseFloat(price));

  return result;
}