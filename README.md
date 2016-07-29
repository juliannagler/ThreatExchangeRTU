#ThreatExchange Subscription

A third party Heroku server that will be notified whenever a threat descriptor that has the third party's subscribed tag is updated or created.

#Steps
1. Create a FB app and register it with ThreatExchange\n
2. Set up an external server such as Heroku.\n
3. Enable Webhooks in your app, subscribe to 'threat_descriptor' via the Webhooks Graph API under 'Application', and set your server website as the callback URL using https://phabricator.intern.facebook.com/P56512611?__mref=message as index.php. For more information: https://developers.facebook.com/docs/graph-api/webhooks/#setup
