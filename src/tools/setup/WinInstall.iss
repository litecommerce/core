[Setup]
AppName=LiteCommerce: online store builder 30-day trial
AppVerName=LiteCommerce version 2.1
DefaultDirName={pf}\LiteCommerce
DefaultGroupName=LiteCommerce
UninstallDisplayIcon={app}\litecommerce-2.1.0-trial.exe

[Files]
Source: "litecommerce-2.1.0-trial.exe"; DestDir: "{app}"
Source: "cpanel.exe"; DestDir: "{app}"

[Icons]
Name: "{group}\LiteCommerce Installation Wizard"; Filename: "{app}\litecommerce-2.1.0-trial.exe"
Name: "{group}\LiteCommerce Control Panel"; Filename: "{app}\cpanel.exe"
Name: "{group}\Uninstall LiteCommerce"; Filename: "{uninstallexe}"
Name: "{userdesktop}\LiteCommerce Control Panel"; Filename: "{app}\cpanel.exe";

[Run]
Filename: "{app}\litecommerce-2.1.0-trial.exe"; Description: "Continue remote install"; Flags: postinstall
