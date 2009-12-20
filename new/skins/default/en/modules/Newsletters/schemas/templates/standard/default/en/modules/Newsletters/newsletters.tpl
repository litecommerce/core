<widget template="common/dialog.tpl" body="modules/Newsletters/news_subscribe.tpl" head="Newsletter subscription" visible="{target=#news#&!valid}">
<widget target="news" mode="confirm_message" template="common/dialog.tpl" body="modules/Newsletters/confirm_message.tpl" head="Subscription request received">
<widget target="news" mode="subscribe_confirmed" template="common/dialog.tpl" body="modules/Newsletters/subscribe_confirmed.tpl" head="Subscription confirmed">
<widget target="news" mode="unsubscribe_confirmed" template="common/dialog.tpl" body="modules/Newsletters/unsubscribe_confirmed.tpl" head="Unsubscription confirmed">
<widget target="news" mode="subscription_failed" template="common/dialog.tpl" body="modules/Newsletters/failed.tpl" head="Subscription failed">
<widget target="news" mode="unsubscription_failed" template="common/dialog.tpl" body="modules/Newsletters/unsubscription_failed.tpl" head="Unsubscription failed">
<widget target="news" mode="view" template="common/dialog.tpl" body="modules/Newsletters/view_news.tpl" head="News message">
<widget target="news" mode="view_all" template="common/dialog.tpl" body="modules/Newsletters/all_news.tpl" head="News and announcements">
