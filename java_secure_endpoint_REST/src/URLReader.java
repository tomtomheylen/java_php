import java.net.*;
import java.io.*;

public class URLReader {

        public void getUri(String url) throws Exception {
            //"https://api.binance.com/api/v1/klines?symbol=BTCUSDT&interval=5m&limit=10"
                //System.out.println(url);
                URL urlObject = new URL(url);
                URLConnection conn = urlObject.openConnection();
                BufferedReader in = new BufferedReader(new InputStreamReader(conn.getInputStream()));
                String inputLine, output = "";
                while ((inputLine = in.readLine()) != null) {
                    output += inputLine;
                }
                in.close();

                System.out.println(output);

        }
/*
    public static void main(String[] args) throws Exception {
        URL urlObject = new URL("https://api.binance.com/api/v1/klines?symbol=BTCUSDT&interval=5m&limit=10");
        URLConnection conn = urlObject.openConnection();
        BufferedReader in = new BufferedReader(new InputStreamReader(conn.getInputStream()));
        String inputLine, output = "";
        while ((inputLine = in.readLine()) != null) {
            output += inputLine;
        }
        in.close();

        System.out.println(output);
    }
*/

}

    /*
        public static void main(String[] args) throws Exception {
            URLReader url = new URLReader();
            url.getUri("https://api.binance.com/api/v1/klines?symbol=BTCUSDT&interval=5&limit=10");
        }
    */
/*
    public static void main(String[] args)  {
        try {
            URLReader url = new URLReader();
            url.getUri("https://api.binance.com/api/v1/klines?symbol=BTCUSDT&interval=5&limit=10");
        }catch (Exception e){
            System.out.println("error: " + e);
        }
    }
}
*/
