dynacase-yubico-yuser
=====================

Sample module to demonstrate Yubico authentication on Dynacase.

Usage
=====

- Build module with `make`

        $ make

- Install `dynacase-yubico-yuser.webinst`.
- Setup `config/local-dbaccess.php` with the content from `config/local-dbaccess.yubico.sample`, and change the `yubico_api_id` and `yubico_api_key` with your API's id/key obtained from https://upgrade.yubico.com/getapikey/
- Log in as admin to create a new "Yubico User".
- Set "Enable Yubico authentication" to "Yes".
- Input a OTP with your Yubikey in the "Yubico OTP" field.
- Log out.
- Log in with the newly created user.
- Standard users (like `admin`) can log in without Yubico OTP, and Yubikey Users with Yubico authentication enabled will require a valid Yubico OTP.
