import java.net.*;
import java.io.*;
import java.sql.Timestamp;

import javax.crypto.Mac;
import javax.crypto.spec.SecretKeySpec;
import java.text.DecimalFormat;

public class Binance {
    private String apiKey, secretKey, params;
    private String base = "https://api.binance.com/";


    public Binance(String apiKey, String secretKey){
        this.apiKey = apiKey;
        this.secretKey = secretKey;
    }

    public String ping(){
        try {
            return this.request("api/v1/ping", "", "GET");
        }catch (Exception e){
            return e.toString();
        }
    }
    public String candles(String symbol, String interval){
        return candles(symbol, interval, 0);
    }
    public String candles(String symbol, String interval, int limit ){

        params = "symbol="+symbol+"&interval="+interval;
        if(limit != 0 ){
            params += "&limit="+limit;
        }

        try {
            return this.request("api/v1/klines", this.params, "GET");
        }catch (Exception e){
            return e.toString();
        }
    }

    public String testBuy(String symbol, double quantity){
        return testBuy(symbol, quantity, "MARKET", 0d);
    }

    public String testBuy(String symbol, double quantity, String type, double price){
        quantity = Math.round(quantity * 1000000d)/1000000d;//6 decimal places allowed for Binance
        if(type == "MARKET"){
            params = "symbol="+symbol+"&side=BUY&type=MARKET&quantity="+quantity+"&timestamp="+createTimeStamp()+"&recvWindow=10000000";
        }
        if(type == "LIMIT"){
            price = Math.round(price * 1000000d)/1000000d;//6 decimal places allowed for Binance
            params = "timeInForce=GTC&symbol="+symbol+"&side=BUY&type=LIMIT&quantity="+quantity+"&price="+price+"&timestamp="+createTimeStamp()+"&recvWindow=10000000";
        }
        //System.out.println(params);
        try {
            return this.signedRequest("api/v3/order/test", this.params, "POST");
        }catch (Exception e){
            return e.toString();
        }
    }

    public String account(){
        this.params = "timestamp="+this.createTimeStamp();

        try {
            return this.signedRequest("api/v3/account", this.params, "GET");
        }catch (Exception e){
            return e.toString();
        }

    }



    private String createSignature(String secretKey, String params) throws Exception{

        SecretKeySpec key = new SecretKeySpec((secretKey).getBytes("UTF-8"), "HmacSHA256" );
        Mac mac = Mac.getInstance("HmacSHA256");
        mac.init(key);
        byte[] bytes = mac.doFinal(params.getBytes("ASCII"));
        StringBuffer hash = new StringBuffer();

        for (int i = 0; i < bytes.length; i++) {
            String hex = Integer.toHexString(0xFF & bytes[i]);
            if (hex.length() == 1) {
                hash.append('0');
            }
            hash.append(hex);
        }
        return hash.toString();
    }

    private long createTimeStamp(){
        Timestamp timestamp = new Timestamp(System.currentTimeMillis());
        long timeStampMillis = timestamp.getTime();
        return timeStampMillis;
    }


    private String request(String api, String params, String method) throws Exception{
        String queryString = this.base+api;
        if(params != "")
            queryString += "?"+params;
        return connect(queryString, method);

    }

    private String signedRequest(String api, String params, String method) throws Exception{

        String signature = createSignature(secretKey,params);
        String queryString = this.base+api+"?"+params+"&signature="+signature;
        //System.out.println(queryString);
        return connect(queryString, method);
    }

    private String connect(String queryString, String method) throws Exception{
        URL uri = new URL(queryString);
        HttpURLConnection con = (HttpURLConnection) uri.openConnection();
        con.setRequestMethod(method);
        con.setRequestProperty("Content-type", "application/x-www-form-urlencoded");
        con.setRequestProperty("X-MBX-APIKEY", this.apiKey);
        int statusCode = con.getResponseCode();
        String result = "";
        BufferedReader br;
        if (statusCode >= 200 && statusCode < 400) {
            // Create an InputStream in order to extract the response object
            br = new BufferedReader(new InputStreamReader(con.getInputStream()));
        }
        else {
            int responseCode = con.getResponseCode();
            br = new BufferedReader(new InputStreamReader(con.getErrorStream()));
            result += responseCode;
        }

        String output;

        while ((output = br.readLine()) != null)
            result += output;

        con.disconnect();
        return result;
    }
}
