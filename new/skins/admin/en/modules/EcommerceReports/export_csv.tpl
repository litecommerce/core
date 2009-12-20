{foreach:targetProfiles,tpid,profile}
{if:export_fields.profile_id}{profile.profile_id}{delimiter}{end:}
{if:export_fields.name}{profile.billing_title:h} {profile.billing_firstname:h} {profile.billing_lastname:h}{delimiter}{end:}
{if:export_fields.email}{profile.login:h}{delimiter}{end:}
{if:export_fields.billing_info}{profile.billing_phone:h}{delimiter}{profile.billing_fax:h}{delimiter}{profile.billing_company:h}{delimiter}{profile.billing_address:h}{delimiter}{profile.billing_city:h}{delimiter}{profile.billingState.state:h}{delimiter}{profile.billingCountry.country:h}{delimiter}{profile.billing_zipcode:h}{end:}
{if:export_fields.shipping_info}{profile.shipping_phone:h}{delimiter}{profile.shipping_fax:h}{delimiter}{profile.shipping_company:h}{delimiter}{profile.shipping_address:h}{delimiter}{profile.shipping_city:h}{delimiter}{profile.billingState.state:h}{delimiter}{profile.billingCountry.country:h}{delimiter}{profile.shipping_zipcode:h}{end:}
{crlf}
{end:}
