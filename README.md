Simplify payment example
=========================
This is an companion example to help developers start building a mobile application using Simplify Commerce by MasterCard to accept payments.

This example charges a set amount to a card token and is built specifically for a quick deploy on a platform such as heroku.


Customization
-------------

### Simplify API Keys

Firstly, you need to configure your Simplify API keys.  Visit simplify.com to sign up for an account if you haven't done so.  Login to your account and you will find your keys in the 'Settings' -> 'API Keys'.

Copy your keys and change the charge.php accordingly.

```php

Simplify::$publicKey = 'YOUR_PUBLIC_API_KEY'; // such as  'sbpb_MzIxYmFjYzItYThiYS00ZDA3LTllZTctY2ZjYjIxY2QzYWMw';
Simplify::$privateKey =  'YOUR_PRIVATE_API_KEY'; // such as 'gEEh+NSgYUi4dqG+u3F3iTuOK4n1L01StM60skz7CUR5YFFQL0ODSXAOkNtXTToq';

```

### Heroku instructions

* Get a heroku account if you don't have one.

* Clone git repository locally.
```shell
$ git clone https://github.com/simplifycom/simplify_payment_examples.git
```
* Change keys as shown above.
* Make sure changes are reflected in your local git repository
```shell
$ git add .
$ git commit -m 'changed keys'
```
* Create an application on heroku
```shell
$ heroku create
```
* Push your app to server
```shell
$ git push heroku master
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
And you are done!

