# Email Api

[![Build Status](https://app.travis-ci.com/alex-kalanis/email-api.svg?branch=master)](https://app.travis-ci.com/github/alex-kalanis/email-api)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alex-kalanis/email-api/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alex-kalanis/email-api/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/alex-kalanis/email-api/v/stable.svg?v=1)](https://packagist.org/packages/alex-kalanis/email-api)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.3-8892BF.svg)](https://php.net/)
[![Downloads](https://img.shields.io/packagist/dt/alex-kalanis/email-api.svg?v1)](https://packagist.org/packages/alex-kalanis/email-api)
[![License](https://poser.pugx.org/alex-kalanis/email-api/license.svg?v=1)](https://packagist.org/packages/alex-kalanis/email-api)
[![Code Coverage](https://scrutinizer-ci.com/g/alex-kalanis/email-api/badges/coverage.png?b=master&v=1)](https://scrutinizer-ci.com/g/alex-kalanis/email-api/?branch=master)

Sending emails - decoupled creating and processing 

Contains libraries for sending emails via bunch of services.

# PHP Installation

```
{
    "require": {
        "alex-kalanis/email-api": "1.0"
    }
}
```

(Refer to [Composer Documentation](https://github.com/composer/composer/blob/master/doc/00-intro.md#introduction) if you are not
familiar with composer)


# PHP Usage

1.) Use your autoloader (if not already done via Composer autoloader)

2.) Add some external packages with connection to the local or remote services.

3.) Connect the "kalanis\EmailApi\Sending" into your app. Extends it for setting your case.

4.) Extend your libraries by interfaces inside the package.

5.) Just call sending.

# Python Installation

into your "setup.py":

```
    install_requires=[
        'kw_email',
    ]
```

# Python Usage

1.) Connect the "kw_email.sending" into your app. When it came necessary
you can extends every library to comply your use-case; mainly your sending agent.
