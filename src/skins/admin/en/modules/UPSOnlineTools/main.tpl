<widget target="ups_config" template="modules/UPSOnlineTools/dialog_ups.tpl" body="modules/UPSOnlineTools/settings/main.tpl" head=" - Settings"/>
<widget target="ups_config" template="modules/UPSOnlineTools/dialog_ups.tpl" body="modules/UPSOnlineTools/settings/test.tpl" head=" - Test"/>

<widget target="ups_online_tool" template="modules/UPSOnlineTools/dialog_ups.tpl" body="modules/UPSOnlineTools/settings/settings.tpl" head="- Main page" IF="currentStep=#0#"/>
<widget target="ups_online_tool" template="modules/UPSOnlineTools/dialog_ups.tpl" body="modules/UPSOnlineTools/settings/settings_1.tpl" head="License and Registration Wizard - Step 1" toneHeader="1" IF="currentStep=#1#"/>
<widget target="ups_online_tool" template="modules/UPSOnlineTools/dialog_ups.tpl" body="modules/UPSOnlineTools/settings/settings_2.tpl" head="License and Registration Wizard - Step 2" toneHeader="1" IF="currentStep=#2#"/>
<widget target="ups_online_tool" template="modules/UPSOnlineTools/dialog_ups.tpl" body="modules/UPSOnlineTools/settings/settings_3.tpl" head="License and Registration Wizard - Step 3" toneHeader="1" IF="currentStep=#3#"/>
<widget target="ups_online_tool" template="modules/UPSOnlineTools/dialog_ups.tpl" body="modules/UPSOnlineTools/settings/settings_4.tpl" head="License and Registration Wizard - Step 4" toneHeader="1" IF="currentStep=#4#"/>
