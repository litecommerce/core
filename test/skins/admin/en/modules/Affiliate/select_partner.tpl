<select size=7 name=partner_id>
<option IF="allOption" value="" selected="partner_id=##">All</option>
<option FOREACH="partners,partner" value="{partner.profile_id}" selected="partner_id={partner.profile_id}">{partner.billing_firstname} {partner.billing_lastname} &lt;{partner.login}&gt;</option>
</select>

