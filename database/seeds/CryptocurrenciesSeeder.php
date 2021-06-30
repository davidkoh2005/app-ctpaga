<?php
use App\Cryptocurrency;
use Illuminate\Database\Seeder;

class CryptocurrenciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $url="https://api.binance.com/api/v3/exchangeInfo";
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array( 
            "Content-Type: application/json",
        ));

        
        $allListBinace = json_decode(curl_exec($ch), true);
        curl_close($ch);

        $listCrypto = array();


        $data = file_get_contents(public_path()."/cryptocurrencies/cryptocurrencies.json");
        $listCryptocurrencies = json_decode($data, true);

        foreach($allListBinace['symbols'] as $list)
        {
            foreach ($listCryptocurrencies as $fileCrypto => $nameCrypto){
                if($list['baseAsset'] == $fileCrypto && file_exists(public_path().'/cryptocurrencies/images/'.$fileCrypto.'.png') && $list['quoteAsset'] == 'USDT')
                    Cryptocurrency::updateOrCreate(
                        [
                            "name" => $nameCrypto,
                        ],
                        [
                            "symbol" => $list['symbol'],
                            "baseAsset" => $list['baseAsset'],
                            "quoteAsset" => $list['quoteAsset'],
                        ]
                    );
            }

        }
    }
}
