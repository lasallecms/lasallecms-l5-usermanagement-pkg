Would prefer to use the auth traits as-is, but cannot. 

https://github.com/laravel/framework/blob/5.1/src/Illuminate/Foundation/Auth/RegistersUsers::getRegister() [for branch 5.1] uses the named route "auth.register". Unfortunately (or fortunately), I need the named route "usermanagement::auth.register". So, I need to use my own tweaked version of the Laravel files.  