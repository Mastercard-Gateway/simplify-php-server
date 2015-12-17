Simplify Payment PHP Server [![Deploy](https://www.herokucdn.com/deploy/button.png)](https://heroku.com/deploy)
=========================
This is an companion application to help developers start building mobile applications using Simplify Commerce by MasterCard to accept payments. For more information on how Simplify Commerce works, please go through the overview section of Tutorial at Simplify.com -  https://www.simplify.com/commerce/docs/tutorial/index

##Steps for running

* Register with Heroku (if you haven't done so already)
* Register with [Simplify Commerce](https://www.simplify.com/commerce/login/signup) and you will need API Keys (Under Settings/API Keys) in the next step
* Click on "Deploy to Heroku" button you see above
* Go to index.php from the deployed app for next steps...

##Steps for integrating with iOS & Android apps

### iOS:

```objective-c
    //Copy your new php server URL and append /charge.php, then replace YOUR_HEROKU_URL with that string
    NSURL *url = [NSURL URLWithString:@"YOUR_HEROKU_URL"];

    //Create a post request
    NSMutableURLRequest *request = [NSMutableURLRequest requestWithURL:url];
    request.HTTPMethod = @"POST";

    //Set the request body data to an encoded string containing your token and the charge amount
    NSString *bodyString = [NSString stringWithFormat:@"simplifyToken=%@&amount=%@", token, amount];
    request.HTTPBody = [bodyString dataUsingEncoding:NSUTF8StringEncoding];

    //Create a background data task for charging the card. Be sure to switch back to the application's main thread before adjusting the app's UI
    NSURLSessionDataTask *chargeTask = [[NSURLSession sharedSession] dataTaskWithRequest:request completionHandler:^(NSData * _Nullable data, NSURLResponse * _Nullable response, NSError * _Nullable error) {
    if (error) {
        //Handle data task error
    }
    else{
        NSError *jsonError;
        id object = [NSJSONSerialization JSONObjectWithData:data options:0 error:&jsonError];
        if (jsonError) {
            //Handle JSON error
        }
        else {
            //Handle JSON response
        }
    }
    }];
    //Start the data task
    [chargeTask resume];
```

### Android:
```java
    URL url = null;
    HttpURLConnection con = null;
    try {
       URL url = new URL("http://simplifypay.herokuapp.com//charge.php");
       HttpURLConnection con = (HttpURLConnection) url.openConnection();
        //add reuqest header
        con.setRequestMethod("POST");
        con.setRequestProperty("User-Agent", ""Mozilla/5.0");
        con.setRequestProperty("Accept-Language", "en-US,en;q=0.5");

        String urlParameters = "simplifyToken="+token.getId()+"&amount=1000";

        // Send post request
        con.setDoOutput(true);
        DataOutputStream wr = new DataOutputStream(con.getOutputStream());
        wr.writeBytes(urlParameters);
        wr.flush();
        wr.close();

        int responseCode = con.getResponseCode();
        System.out.println("\nSending 'POST' request to URL : " + url);
        System.out.println("Post parameters : " + urlParameters);
        System.out.println("Response Code : " + responseCode);

        BufferedReader in = new BufferedReader(
                new InputStreamReader(con.getInputStream()));
        String inputLine;
        StringBuffer response = new StringBuffer();

        while ((inputLine = in.readLine()) != null) {
            response.append(inputLine);
        }
        in.close();
        //print result
        System.out.println(response.toString());
        //
    } catch (Exception e) {
        e.printStackTrace();
    } finally {
        con.close();
    }

```

##References
* https://www.simplify.com/commerce/docs/examples/heroku
* https://www.simplify.com/commerce/docs
* https://www.simplify.com/commerce/docs/tutorial/index#payments-form
* https://www.simplify.com/commerce/docs/tutorial/index#testing