Simplify Payment PHP Server [![Deploy](https://www.herokucdn.com/deploy/button.png)](https://heroku.com/deploy)
=========================
This is an companion application to help developers start building mobile applications using Simplify Commerce by MasterCard to accept payments.

##Steps for running

* Register with Heroku (if you haven't done so already)
* Register with [Simplify Commerce](https://www.simplify.com/commerce/login/signup) and you will need API Keys (Under Settings/API Keys) in the next step
* Click on "Deploy to Heroku" button you see above
* Go to index.php from the deployed app for next steps...

###Steps for integrating with iOS & Android apps

```
* Copy the url and paste it in your application. For example in iOS:
```ios
NSURL *url= [NSURL URLWithString:@"http://arcane-ridge-6454.herokuapp.com/charge.php"];
```
* Add code to post
```ios

   //Process Request on your own server
     NSURLResponse *response;
     NSData *responseData = [NSURLConnection sendSynchronousRequest:request returningResponse:&response error:&error];
     NSString *string = [[NSString alloc] initWithData:responseData encoding:NSASCIIStringEncoding];
     NSLog(@"responseData: %@", string);
```
* Or in Android:
```java
String data = "your data";
String response;

URL url = new URL("http://arcane-ridge-6454.herokuapp.com/charge.php");
HttpURLConnection urlConnection = (HttpURLConnection) url.openConnection();
try {
    urlConnection.setDoOutput(true);
    urlConnection.setChunkedStreamingMode(0);

    OutputStream out = new BufferedOutputStream(urlConnection.getOutputStream());
    out.write(data.getBytes("UTF-8"));
    out.close();

    urlConnection.connect();

    InputStream in = new BufferedInputStream(urlConnection.getInputStream());
    StringBuilder sb = new StringBuilder();
    BufferedReader rd = new BufferedReader(new InputStreamReader(is));
    String line = "";
    while ((line = rd.readLine()) != null) {
        sb.append(line);
    }
    in.close();

    response = sb.toString();
}
finally {
    urlConnection.disconnect();
}
```

##References
* https://www.simplify.com/commerce/docs
* https://www.simplify.com/commerce/docs/tutorial/index#payments-form
* https://www.simplify.com/commerce/docs/tutorial/index#testing





