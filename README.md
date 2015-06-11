Simplify Payment PHP Server [![Deploy](https://www.herokucdn.com/deploy/button.png)](https://heroku.com/deploy)
=========================
This is an companion application to help developers start building mobile applications using Simplify Commerce by MasterCard to accept payments. For more information on how Simplify Commerce works, please go through the overview section of Tutorial at Simplify.com -  https://www.simplify.com/commerce/docs/tutorial/index

##Steps for running

* Register with Heroku (if you haven't done so already)
* Register with [Simplify Commerce](https://www.simplify.com/commerce/login/signup) and you will need API Keys (Under Settings/API Keys) in the next step
* Click on "Deploy to Heroku" button you see above
* Go to index.php from the deployed app for next steps...

##Steps for integrating with iOS & Android apps

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
* https://www.simplify.com/commerce/docs
* https://www.simplify.com/commerce/docs/tutorial/index#payments-form
* https://www.simplify.com/commerce/docs/tutorial/index#testing





