# USER MANAGEMENT

[![Build Status](https://img.shields.io/travis/lasallecms/lasallecms-l5-usermanagement-pkg/master.svg?style=flat-square)](https://travis-ci.org/lasallecms/lasallecms-l5-usermanagement-pkg)
[![Total Downloads](https://img.shields.io/packagist/dt/lasallecms/usermanagement.svg?style=flat-square)](https://packagist.org/packages/lasallecms/usermanagement)
[![Latest Stable Version](https://poser.pugx.org/lasallecms/usermanagement/v/stable.svg)](https://packagist.org/packages/lasallecms/usermanagement)
[![Latest Unstable Version](https://poser.pugx.org/lasallecms/usermanagement/v/unstable.svg)](https://packagist.org/packages/lasallecms/usermanagement)
[![GitHub Issues](https://img.shields.io/github/issues/lasallecms/lasallecms-l5-usermanagement-pkg.svg)](https://github.com/lasallecms/lasallecms-l5-usermanagement-pkg/issues)
[![Software License](https://img.shields.io/badge/license-GPLv3-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Laravel](https://img.shields.io/badge/Laravel-v5.1-brightgreen.svg?style=flat-square)](http://laravel.com)


User Management package made specifically for my LaSalle Content Management System. 

I extracted the native L5 app's authentication features into this package. 


*** DO NOT USE THIS REPOSITORY!! NOT MAINTAINED!! GO TO [LaSalleSoftware.ca](https://lasallesoftware.ca) ***



## Usage Caveat

Is it really worthwhile extracting the core auth out of the app? After all, as I learned, it's not just the base L5 app we're dealing with. The two trait files at Illuminate\Foundation\Auth are part of it too. Plus App\Http\Kernel.php too. But, yes, it is, at least I think so.

When I say extract, I am not kidding. I deleted all the auth from the L5 base app that extracted into this app. Even the auth.php config. Chances are pretty good that you are not extracting any auth from your L5 base app. I've not actually run this package with the L5 app, but I'm sure it won't work. Worse, it could even harm your app. 

How's that for a caveat!

## It's Really All About AUTH

This package was originally intended to be an all-encompassing user management package. Auth, front-end registration, back-end user management, roles & permissions. However, as real-life intrudes, this comprehensiveness is not manifesting. 

Instead, it is mercifully easier to place the back-end user management in the -- wait for it! -- Admin package. 

So, this package is named "User Management", yet it is, so far, distilling into a user auth package. 

## Security

If you discover any security related issues, please email Bob Bloom at "info at southlasalle dot com" instead of using the issue tracker.


## Links

* [Note on front-end registration](REGISTRATION.md)
* [CONTRIBUTING](CONTRIBUTING.md)
* [CHANGELOG](CHANGELOG.md)
* [INSTALL](INSTALL.md)
* [GPLv3 License File](LICENSE.md)



