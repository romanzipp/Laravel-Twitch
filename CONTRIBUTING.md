# How to contribute to Laravel Twitch

This guide covers how you can become a part of the ongoing development of Laravel Twitch.

After reading this guide, you will know:

- How to use GitHub to report issues.
- How to clone master and run the test suite.
- How to help resolve existing issues.
- How to contribute to the Laravel Twitch documentation.
- How to contribute to the Laravel Twitch code.

## 1. Reporting an Issue

TBD

## 2. Helping to Resolve Existing Issues

TBD

## 3. Contributing to the Laravel Twitch Documentation

The `README.md` is generated from the `README.stub.md` file via the `composer docs` command.
So if you want to change something in the documentation, make your changes in the stub file and run the composer command. 
Otherwise, your changes will be rolled back at a later time.

## 4. Contributing to the Laravel Twitch Code

### 4.X. Setting Up a Development Environment

To be able to test your changes you need to have the following packages installed:

- PHP 7.2
- Composer 2

Here is an example how to install PHP and Composer on Linux (Debian-like, Ubuntu-like):

```bash
# Keep your OS fresh and add the ppa:ondrej/php repository
sudo apt-get update && apt-get upgrade
sudo add-apt-repository ppa:ondrej/php
# Sometimes add-apt-repository already do this for you, on older versions not
sudo apt-get update
# Install PHP and other required dependencies
sudo apt-get install php-common php-cli php-zip unzip wget
```

```bash
# Download the latest composer version
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
# Verify the data integrity of the script
HASH="$(wget -q -O - https://composer.github.io/installer.sig)"
php -r "if (hash_file('SHA384', 'composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
# Install Composer in the /usr/local/bin directory
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
```

### 4.X. Clone the Laravel Twitch Repository

To be able to contribute code, you need to clone the Laravel Twitch repository:

```bash
git clone https://github.com/romanzipp/Laravel-Twitch.git
```

and create a dedicated branch:

```bash
cd Laravel-Twitch
git checkout -b my-new-branch
```

### 4.X. Install Dependencies

Install the required dependencies.

```bash
composer install
```

### 4.X. Write Your Code

Now get busy and add/edit code. You're on your branch now, so you can write whatever you want (make sure you're on the right branch with `git branch -a`).

### 4.X. Running Tests

To run all test, do:

```bash
composer test
```

With environment variables, do:

```bash
CLIENT_ID=xxxx composer test
```

```bash
CLIENT_ID=xxxx CLIENT_SECRET=xxxx composer test
```

### 4.X. Cleanup Your Chaos

After many lines of code, you may be faced with a bit of a mess.  To avoid the GitHub pipeline cleaning up for you, do:

```bash
composer fix
```

### 4.X. Commit Your Changes

When you're happy with the code on your computer, you need to commit the changes to Git:

```bash
git commit -a
```

This should fire up your editor to write a commit message. When you have finished, save, and close to continue.

### 4.X. Update Your Branch

It's pretty likely that other changes to master have happened while you were working. Go get them:

```bash
git checkout master
git pull --rebase
```

Now reapply your patch on top of the latest changes:

```bash
git checkout my-new-branch
git rebase master
```

No conflicts? Tests still pass? Change still seems reasonable to you? Then move on.

### 4.X. Issue a Pull Request

On GitHub, you can easily create a new [pull request](https://github.com/romanzipp/Laravel-Twitch/compare).

Ensure the changesets you introduced are included. Fill in some details about your potential patch, including a meaningful title. When finished, press "Send pull request". The Laravel Twitch contributors will be notified about your submission.

## Feedback

You're encouraged to help improve the quality of this guide.