public class Main {

    public static void main(String[] args) throws Exception {
        String apiKey = "key";
        String apiSecret = "secret";
        Binance test = new Binance(apiKey,apiSecret);
        //System.out.println(test.ping());
        //System.out.println(test.account());


        //System.out.println(test.candles("BTCUSDT", "3m",2 ));
        System.out.println(test.testBuy("BTCUSDT", 0.25867821587d, "LIMIT", 10000.00d ));
        //System.out.println(test.testBuy("BTCUSDT", 0.25867821587d ));

    }
}
//https://api.binance.com/api/v1/klines?symbol=BTCUSDT&interval=5m&limit=10