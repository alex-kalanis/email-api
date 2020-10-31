from setuptools import find_packages, setup

from kw_email import __version__ as version

with open("README.md", "r") as fh:
    long_desc = fh.read()

setup(
    name='kw_email',
    version=version,
    license='BSD',
    author='Petr Plsek',
    author_email='me@kalanys.com',
    description='A library for sending emails',
    long_description=long_desc,
    long_description_content_type='text/markdown',
    url='https://github.com/alex-kalanis/email-api',
    install_require=[
    ],
    packages=find_packages(),
    classifiers=[
        'Environment :: Web Environment',
        'License :: OSI Approved :: BSD License',
        'Operating System :: OS Independent',
        'Programming Language :: Python',
        'Programming Language :: Python :: 3.5',
        'Topic :: Software Development :: Libraries :: Python Modules',
    ],
)