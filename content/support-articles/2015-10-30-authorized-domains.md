---
title: Authorized Domains
slug: authorized-domains
date: 2015-10-30 8:00 PM
---

BuzzingPixel commercial software and add-ons validate their license keys against the authorized domain you have set for that license key. At this time, only Ansel does this but more software will follow suit.

In general terms, the license for BuzzingPixel commercial software and add-ons states that each license is authorized to run on one public domain and any ancillary domains to support that project/site. This includes staging and non-public environments to go alone with **one** user-facing public website.

In order for your license to validate, you will need to provide an authorized domain for that license [in your account](/account). Include only the base domain name. Example: `buzzingpixel.com`. **Do not include sub directories or sub domains.**

The license validation process checks for a number of things when validating your license and domain. Primarily, it will check your license key and the domain it's running on against the records in the database. If it finds a match for the key and the authorized domain you entered, everything is good to go.

If the domain and license key do not match, there will be trouble in paradise.

## The Validation Process

The validation process checks a number of things. For instance, the domain will only be part of the validation process if it:

- Contains at least two parts (parts are separated by periods)
- Does not have a `.dev` TLD
- Is not an IP address
- Does not contain a port specification
- The sub-domain does not sound "dev-y" (e.g. staging.mydomain.com). Dev-y sub-domains are currently:
    - demo,
    - dev,
    - local,
    - loc,
    - test,
    - testing,
    - sandbox,
    - stage,
    - staging,
    - acc,
    - acceptance
    - If I have missed any that you use, please let me know. I'd rather err on the side of letting too many through than being too restrictive

If any of those are true, only the license key is checked.

Once it is determined that the domain is part of the license key validation check, only the base part of the domain will be checked (e.g. mydomain.com). So do not enter as your authorized domain something like "mydomain.com/mysubdir"
