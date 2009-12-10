object f_settings: Tf_settings
  Left = 0
  Top = 0
  Width = 467
  Height = 443
  AutoScroll = False
  TabOrder = 0
  object Panel1: TPanel
    Left = 0
    Top = 402
    Width = 467
    Height = 41
    Align = alBottom
    BevelOuter = bvNone
    TabOrder = 0
    object _cancel: TButton
      Left = 384
      Top = 8
      Width = 75
      Height = 25
      Anchors = [akTop, akRight]
      Caption = 'Cancel'
      TabOrder = 2
      OnClick = _cancelClick
    end
    object _continue: TButton
      Left = 304
      Top = 8
      Width = 75
      Height = 25
      Anchors = [akTop, akRight]
      Caption = 'Continue..'
      Default = True
      TabOrder = 0
      OnClick = _continueClick
    end
    object _help: TButton
      Left = 8
      Top = 8
      Width = 75
      Height = 25
      Caption = 'Help'
      TabOrder = 1
      OnClick = _helpClick
    end
  end
  object __ftp: TGroupBox
    Left = 0
    Top = 0
    Width = 467
    Height = 153
    Align = alTop
    Caption = 'FTP server settings'
    TabOrder = 1
    object Label1: TLabel
      Left = 56
      Top = 26
      Width = 38
      Height = 13
      Caption = 'Address'
    end
    object Label2: TLabel
      Left = 352
      Top = 26
      Width = 19
      Height = 13
      Caption = 'Port'
    end
    object Label3: TLabel
      Left = 16
      Top = 82
      Width = 77
      Height = 13
      Caption = 'Upload directory'
    end
    object Label4: TLabel
      Left = 64
      Top = 114
      Width = 26
      Height = 13
      Caption = 'Login'
    end
    object Label5: TLabel
      Left = 248
      Top = 114
      Width = 46
      Height = 13
      Caption = 'Password'
    end
    object ftp_server: TEdit
      Left = 104
      Top = 24
      Width = 201
      Height = 21
      TabOrder = 0
      Text = 'rrf.ru'
    end
    object ftp_port: TEdit
      Left = 384
      Top = 24
      Width = 57
      Height = 21
      TabOrder = 1
      Text = '21'
    end
    object ftp_dir: TEdit
      Left = 104
      Top = 80
      Width = 337
      Height = 21
      TabOrder = 3
      Text = 'public_html/test2'
    end
    object passive_mode: TCheckBox
      Left = 104
      Top = 56
      Width = 241
      Height = 13
      Caption = 'Use passive mode'
      TabOrder = 2
    end
    object ftp_login: TEdit
      Left = 104
      Top = 112
      Width = 137
      Height = 21
      TabOrder = 4
      Text = 'ivf'
    end
    object ftp_pass: TEdit
      Left = 304
      Top = 112
      Width = 137
      Height = 21
      PasswordChar = '*'
      TabOrder = 5
    end
  end
  object __shop: TGroupBox
    Left = 0
    Top = 153
    Width = 467
    Height = 88
    Align = alTop
    Caption = 'Shop settings'
    TabOrder = 2
    object Label6: TLabel
      Left = 16
      Top = 24
      Width = 73
      Height = 13
      Caption = 'Your shop URL'
    end
    object Label7: TLabel
      Left = 104
      Top = 56
      Width = 203
      Height = 13
      Caption = 'Example: http://www.yourstore.com/shop/'
    end
    object http_address: TEdit
      Left = 104
      Top = 24
      Width = 337
      Height = 21
      TabOrder = 0
      Text = 'http://rrf.ru/~ivf/test2'
    end
  end
  object __proxy: TGroupBox
    Left = 0
    Top = 241
    Width = 467
    Height = 144
    Align = alTop
    Caption = 'Proxy settings'
    TabOrder = 3
    object Label8: TLabel
      Left = 64
      Top = 49
      Width = 38
      Height = 13
      Caption = 'Address'
    end
    object Label9: TLabel
      Left = 352
      Top = 49
      Width = 19
      Height = 13
      Caption = 'Port'
    end
    object Label10: TLabel
      Left = 256
      Top = 107
      Width = 46
      Height = 13
      Caption = 'Password'
    end
    object Label11: TLabel
      Left = 72
      Top = 107
      Width = 26
      Height = 13
      Caption = 'Login'
    end
    object use_proxy: TCheckBox
      Left = 112
      Top = 22
      Width = 97
      Height = 17
      Caption = 'Use proxy'
      TabOrder = 0
      OnClick = use_proxyClick
    end
    object proxy_server: TEdit
      Left = 112
      Top = 48
      Width = 193
      Height = 21
      TabOrder = 1
    end
    object proxy_port: TEdit
      Left = 384
      Top = 48
      Width = 57
      Height = 21
      TabOrder = 2
    end
    object proxy_auth: TCheckBox
      Left = 112
      Top = 77
      Width = 169
      Height = 17
      Caption = 'Use proxy login and password'
      TabOrder = 3
      OnClick = proxy_authClick
    end
    object proxy_login: TEdit
      Left = 112
      Top = 104
      Width = 137
      Height = 21
      TabOrder = 4
    end
    object proxy_pass: TEdit
      Left = 312
      Top = 104
      Width = 129
      Height = 21
      PasswordChar = '*'
      TabOrder = 5
    end
  end
end
