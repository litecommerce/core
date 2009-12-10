object add_shop_frm: Tadd_shop_frm
  Left = 0
  Top = 0
  Width = 535
  Height = 368
  TabOrder = 0
  object Panel1: TPanel
    Left = 0
    Top = 0
    Width = 535
    Height = 368
    Align = alClient
    BevelOuter = bvNone
    TabOrder = 0
    object Panel3: TPanel
      Left = 0
      Top = 327
      Width = 535
      Height = 41
      Align = alBottom
      BevelOuter = bvNone
      TabOrder = 0
      object shop_save: TButton
        Left = 24
        Top = 8
        Width = 75
        Height = 25
        Caption = 'Save'
        TabOrder = 0
        OnClick = shop_saveClick
      end
    end
    object Panel2: TPanel
      Left = 0
      Top = 0
      Width = 535
      Height = 327
      Align = alClient
      BevelOuter = bvNone
      TabOrder = 1
      object ftp_settings: TGroupBox
        Left = 0
        Top = 0
        Width = 257
        Height = 225
        Align = alLeft
        Caption = 'Ftp settings'
        TabOrder = 0
        object Label2: TLabel
          Left = 16
          Top = 32
          Width = 38
          Height = 13
          Caption = 'Ftp host'
        end
        object Label3: TLabel
          Left = 16
          Top = 56
          Width = 36
          Height = 13
          Caption = 'Ftp port'
        end
        object Label4: TLabel
          Left = 16
          Top = 104
          Width = 40
          Height = 13
          Caption = 'Ftp login'
        end
        object Label5: TLabel
          Left = 16
          Top = 128
          Width = 63
          Height = 13
          Caption = 'Ftp password'
        end
        object Label6: TLabel
          Left = 16
          Top = 80
          Width = 58
          Height = 13
          Caption = 'Ftp directory'
        end
        object ftp_host: TEdit
          Left = 96
          Top = 24
          Width = 153
          Height = 21
          Anchors = [akLeft, akTop, akRight]
          TabOrder = 0
        end
        object ftp_port: TEdit
          Left = 96
          Top = 48
          Width = 153
          Height = 21
          Anchors = [akLeft, akTop, akRight]
          TabOrder = 1
        end
        object ftp_dir: TEdit
          Left = 96
          Top = 72
          Width = 153
          Height = 21
          Anchors = [akLeft, akTop, akRight]
          TabOrder = 2
        end
        object ftp_login: TEdit
          Left = 96
          Top = 96
          Width = 153
          Height = 21
          Anchors = [akLeft, akTop, akRight]
          TabOrder = 3
        end
        object ftp_password: TEdit
          Left = 96
          Top = 120
          Width = 153
          Height = 21
          Anchors = [akLeft, akTop, akRight]
          PasswordChar = '*'
          TabOrder = 4
        end
      end
      object mysql_settings: TGroupBox
        Left = 257
        Top = 0
        Width = 278
        Height = 225
        Align = alClient
        Caption = 'MySQL settings'
        TabOrder = 1
        object Label7: TLabel
          Left = 16
          Top = 32
          Width = 58
          Height = 13
          Caption = 'MySQL host'
        end
        object Label8: TLabel
          Left = 16
          Top = 56
          Width = 82
          Height = 13
          Caption = 'MySQL database'
        end
        object Label9: TLabel
          Left = 16
          Top = 80
          Width = 60
          Height = 13
          Caption = 'MySQL login'
        end
        object Label10: TLabel
          Left = 16
          Top = 104
          Width = 83
          Height = 13
          Caption = 'MySQL password'
        end
        object mysql_host: TEdit
          Left = 104
          Top = 24
          Width = 162
          Height = 21
          Anchors = [akLeft, akTop, akRight]
          TabOrder = 0
        end
        object mysql_db: TEdit
          Left = 104
          Top = 48
          Width = 162
          Height = 21
          Anchors = [akLeft, akTop, akRight]
          TabOrder = 1
        end
        object mysql_login: TEdit
          Left = 104
          Top = 72
          Width = 162
          Height = 21
          Anchors = [akLeft, akTop, akRight]
          TabOrder = 2
        end
        object mysql_password: TEdit
          Left = 104
          Top = 96
          Width = 162
          Height = 21
          Anchors = [akLeft, akTop, akRight]
          PasswordChar = '*'
          TabOrder = 3
        end
      end
      object shop_settings: TGroupBox
        Left = 0
        Top = 225
        Width = 535
        Height = 102
        Align = alBottom
        Caption = 'Shop settings'
        TabOrder = 2
        object Label11: TLabel
          Left = 16
          Top = 24
          Width = 50
          Height = 13
          Caption = 'Shop URL'
        end
        object Label12: TLabel
          Left = 16
          Top = 48
          Width = 90
          Height = 13
          Caption = 'Administrator e-mail'
        end
        object Label13: TLabel
          Left = 16
          Top = 72
          Width = 108
          Height = 13
          Caption = 'Administrator password'
        end
        object http_url: TEdit
          Left = 136
          Top = 16
          Width = 313
          Height = 21
          TabOrder = 0
        end
        object admin_login: TEdit
          Left = 136
          Top = 40
          Width = 121
          Height = 21
          TabOrder = 1
        end
        object admin_password: TEdit
          Left = 136
          Top = 64
          Width = 121
          Height = 21
          PasswordChar = '*'
          TabOrder = 2
        end
      end
    end
  end
end
