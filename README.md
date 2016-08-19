#ThreatExchange Subscription

A third party Heroku server that will be notified whenever a threat descriptor that has the third party's subscribed tag is updated or created.

#Steps
1. Create a FB app and register it with ThreatExchange.
2. Set up an external server such as Heroku using this code.
3. Enable Webhooks in your app, subscribe to 'threat_descriptor' or 'malware_analysis'  via the Webhooks Graph API under 'Threat Exchange', and set your server website as the callback URL using [this] (https://phabricator.intern.facebook.com/P56512611?__mref=message) as index.php. Click [here] (https://developers.facebook.com/docs/graph-api/webhooks/#setup) for more information.
