# Email Api

Sending emails - decoupled creating and processing 

Contains libraries for sending emails via bunch of services.

# PHP Installation

```
{
    "require": {
        "alex-kalanis/email-api": "dev-master"
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
