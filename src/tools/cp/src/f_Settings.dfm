object settings_frm: Tsettings_frm
  Left = 0
  Top = 0
  Width = 544
  Height = 433
  TabOrder = 0
  OnResize = FrameResize
  object settings_bkgr: TPanel
    Left = 0
    Top = 0
    Width = 544
    Height = 433
    Align = alClient
    BevelOuter = bvLowered
    TabOrder = 0
    object settings_pages: TPageControl
      Left = 1
      Top = 42
      Width = 542
      Height = 390
      ActivePage = shop_opts
      Align = alClient
      TabOrder = 0
      OnChange = settings_pagesChange
      object shop_opts: TTabSheet
        Caption = 'Shop'
        object Panel1: TPanel
          Left = 0
          Top = 0
          Width = 534
          Height = 362
          Align = alClient
          BevelOuter = bvLowered
          TabOrder = 0
          object Panel2: TPanel
            Left = 1
            Top = 1
            Width = 532
            Height = 41
            Align = alTop
            BevelOuter = bvNone
            TabOrder = 0
            object Label1: TLabel
              Left = 10
              Top = 18
              Width = 56
              Height = 13
              Caption = 'Select shop'
            end
            object shops: TComboBox
              Left = 94
              Top = 16
              Width = 305
              Height = 21
              Style = csDropDownList
              ItemHeight = 13
              TabOrder = 0
              OnChange = shopsChange
            end
          end
          object Panel3: TPanel
            Left = 1
            Top = 320
            Width = 532
            Height = 41
            Align = alBottom
            BevelOuter = bvNone
            TabOrder = 2
            object shop_save: TButton
              Left = 24
              Top = 8
              Width = 75
              Height = 25
              Caption = 'Save'
              TabOrder = 0
              OnClick = shop_saveClick
            end
            object shop_delete: TButton
              Left = 112
              Top = 8
              Width = 75
              Height = 25
              Caption = 'Delete'
              TabOrder = 1
              OnClick = shop_deleteClick
            end
            object test_settings: TButton
              Left = 200
              Top = 8
              Width = 75
              Height = 25
              Caption = 'Test settings'
              TabOrder = 2
              OnClick = test_settingsClick
            end
          end
          object Panel4: TPanel
            Left = 1
            Top = 42
            Width = 532
            Height = 278
            Align = alClient
            BevelOuter = bvNone
            TabOrder = 1
            object mysql_settings: TGroupBox
              Left = 296
              Top = 0
              Width = 233
              Height = 198
              BiDiMode = bdRightToLeftNoAlign
              Caption = 'MySQL settings'
              ParentBiDiMode = False
              TabOrder = 2
              object Label7: TLabel
                Left = 24
                Top = 27
                Width = 22
                Height = 13
                Caption = 'Host'
              end
              object Label8: TLabel
                Left = 25
                Top = 50
                Width = 46
                Height = 13
                Caption = 'Database'
              end
              object Label9: TLabel
                Left = 25
                Top = 75
                Width = 26
                Height = 13
                Caption = 'Login'
              end
              object Label10: TLabel
                Left = 25
                Top = 102
                Width = 46
                Height = 13
                Caption = 'Password'
              end
              object mysql_host: TEdit
                Left = 91
                Top = 20
                Width = 120
                Height = 21
                Anchors = [akLeft, akTop, akRight]
                TabOrder = 0
              end
              object mysql_db: TEdit
                Left = 91
                Top = 46
                Width = 120
                Height = 21
                Anchors = [akLeft, akTop, akRight]
                TabOrder = 1
              end
              object mysql_login: TEdit
                Left = 91
                Top = 71
                Width = 120
                Height = 21
                Anchors = [akLeft, akTop, akRight]
                TabOrder = 2
              end
              object mysql_password: TEdit
                Left = 91
                Top = 98
                Width = 120
                Height = 21
                Anchors = [akLeft, akTop, akRight]
                PasswordChar = '*'
                TabOrder = 3
              end
            end
            object ftp_settings: TGroupBox
              Left = 0
              Top = 0
              Width = 289
              Height = 198
              Caption = 'FTP server settings'
              Constraints.MaxWidth = 400
              TabOrder = 0
              object Label2: TLabel
                Left = 9
                Top = 20
                Width = 38
                Height = 13
                Caption = 'Address'
              end
              object Label6: TLabel
                Left = 9
                Top = 72
                Width = 77
                Height = 13
                Caption = 'Upload directory'
              end
              object Label4: TLabel
                Left = 9
                Top = 100
                Width = 26
                Height = 13
                Caption = 'Login'
              end
              object Label5: TLabel
                Left = 9
                Top = 127
                Width = 46
                Height = 13
                Caption = 'Password'
              end
              object Label3: TLabel
                Left = 218
                Top = 20
                Width = 19
                Height = 13
                Caption = 'Port'
              end
              object ftp_passive: TCheckBox
                Left = 95
                Top = 43
                Width = 105
                Height = 17
                Caption = 'Use passive mode'
                TabOrder = 2
              end
              object ftp_password: TEdit
                Left = 94
                Top = 123
                Width = 178
                Height = 21
                Anchors = [akLeft, akTop, akRight]
                PasswordChar = '*'
                TabOrder = 5
              end
              object ftp_login: TEdit
                Left = 94
                Top = 95
                Width = 178
                Height = 21
                Anchors = [akLeft, akTop, akRight]
                TabOrder = 4
              end
              object ftp_dir: TEdit
                Left = 94
                Top = 68
                Width = 178
                Height = 21
                Anchors = [akLeft, akTop, akRight]
                TabOrder = 3
              end
              object ftp_host: TEdit
                Left = 94
                Top = 17
                Width = 119
                Height = 21
                Anchors = [akLeft, akTop, akRight]
                CharCase = ecLowerCase
                TabOrder = 0
              end
              object ftp_port: TEdit
                Left = 244
                Top = 17
                Width = 27
                Height = 21
                Anchors = [akLeft, akTop, akRight]
                TabOrder = 1
                Text = '21'
              end
            end
            object GroupBox2: TGroupBox
              Left = 0
              Top = 198
              Width = 532
              Height = 80
              Align = alBottom
              Caption = 'Shop settings'
              TabOrder = 1
              object Label11: TLabel
                Left = 12
                Top = 22
                Width = 50
                Height = 13
                Caption = 'Shop URL'
              end
              object Label12: TLabel
                Left = 12
                Top = 44
                Width = 90
                Height = 13
                Caption = 'Administrator e-mail'
              end
              object Label13: TLabel
                Left = 260
                Top = 45
                Width = 108
                Height = 13
                Caption = 'Administrator password'
              end
              object http_url: TEdit
                Left = 132
                Top = 17
                Width = 369
                Height = 21
                CharCase = ecLowerCase
                TabOrder = 0
              end
              object admin_login: TEdit
                Left = 132
                Top = 42
                Width = 121
                Height = 21
                TabOrder = 1
              end
              object admin_password: TEdit
                Left = 380
                Top = 43
                Width = 121
                Height = 21
                PasswordChar = '*'
                TabOrder = 2
              end
            end
          end
        end
      end
      object proxy_opts: TTabSheet
        Caption = 'Proxy'
        ImageIndex = 1
        object ftp_props_bkgr: TPanel
          Left = 0
          Top = 0
          Width = 534
          Height = 362
          Align = alClient
          BevelOuter = bvLowered
          TabOrder = 0
          object Panel5: TPanel
            Left = 1
            Top = 278
            Width = 512
            Height = 39
            BevelOuter = bvNone
            TabOrder = 0
            object proxy_save: TButton
              Left = 24
              Top = 8
              Width = 75
              Height = 25
              Caption = 'Save'
              TabOrder = 0
              OnClick = proxy_saveClick
            end
          end
          object proxy_serv_port: TGroupBox
            Left = 1
            Top = 1
            Width = 532
            Height = 257
            Align = alTop
            Caption = 'HTTP Proxy options'
            TabOrder = 1
            object use_proxy: TCheckBox
              Left = 15
              Top = 32
              Width = 97
              Height = 17
              Caption = 'Use proxy'
              TabOrder = 0
              OnClick = use_proxyClick
            end
            object use_proxy_login: TCheckBox
              Left = 15
              Top = 124
              Width = 169
              Height = 17
              Caption = 'Proxy authentication'
              TabOrder = 1
              OnClick = use_proxy_loginClick
            end
            object Panel8: TPanel
              Left = 15
              Top = 155
              Width = 481
              Height = 75
              BevelInner = bvRaised
              BevelOuter = bvLowered
              TabOrder = 2
              object Label17: TLabel
                Left = 15
                Top = 47
                Width = 46
                Height = 13
                Caption = 'Password'
              end
              object Label16: TLabel
                Left = 15
                Top = 16
                Width = 26
                Height = 13
                Caption = 'Login'
              end
              object proxy_login: TEdit
                Left = 100
                Top = 11
                Width = 177
                Height = 21
                TabOrder = 0
                Text = 'Proxy login'
              end
              object proxy_password: TEdit
                Left = 100
                Top = 44
                Width = 177
                Height = 21
                PasswordChar = '*'
                TabOrder = 1
              end
            end
            object Panel9: TPanel
              Left = 15
              Top = 61
              Width = 480
              Height = 49
              BevelInner = bvRaised
              BevelOuter = bvLowered
              TabOrder = 3
              object Label14: TLabel
                Left = 16
                Top = 18
                Width = 38
                Height = 13
                Caption = 'Address'
              end
              object Label15: TLabel
                Left = 397
                Top = 17
                Width = 19
                Height = 13
                Caption = 'Port'
              end
              object proxy_server: TEdit
                Left = 100
                Top = 14
                Width = 285
                Height = 21
                TabOrder = 0
                Text = 'Proxy Server'
              end
              object proxy_port: TEdit
                Left = 428
                Top = 14
                Width = 37
                Height = 21
                TabOrder = 1
              end
            end
          end
        end
      end
      object apps_opts: TTabSheet
        Caption = 'Default HTML editor'
        ImageIndex = 2
        object Panel6: TPanel
          Left = 0
          Top = 0
          Width = 534
          Height = 362
          Align = alClient
          BevelInner = bvLowered
          BevelOuter = bvNone
          TabOrder = 0
          object SpeedButton1: TSpeedButton
            Left = 460
            Top = 72
            Width = 23
            Height = 22
            Anchors = [akTop, akRight]
            Flat = True
            Glyph.Data = {
              CA050000424DCA05000000000000360000002800000016000000150000000100
              18000000000094050000C40E0000C40E00000000000000000000C8D0D4C8D0D4
              C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0
              D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D40000C8D0
              D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8
              D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4
              0000C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4
              C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0
              D4C8D0D40000C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0
              D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8
              D0D4C8D0D4C8D0D40000C8D0D4C8D0D400000000000000000000000000000000
              0000000000000000000000000000000000C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4
              C8D0D4C8D0D4C8D0D4C8D0D40000C8D0D4C8D0D4000000000000FF8080FF8080
              FF8080FF8080FF8080FF8080FF8080FF8080FF8080000000C8D0D4C8D0D4C8D0
              D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D40000C8D0D4C8D0D4000000FFFF000000
              00FF8080FF8080FF8080FF8080FF8080FF8080FF8080FF8080FF8080000000C8
              D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D40000C8D0D4C8D0D4000000FF
              FF00FFFF00000000FF8080FF8080FF8080FF8080FF8080FF8080FF8080FF8080
              FF8080000000C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D40000C8D0D4C8D0D4
              000000FFFF00FFFF00FFFF00000000FF8080FF8080FF8080FF8080FF8080FF80
              80FF8080FF8080FF8080000000C8D0D4C8D0D4C8D0D4C8D0D4C8D0D40000C8D0
              D4C8D0D4000000FFFF00FFFF00FFFF00FFFF0000000000000000000000000000
              0000000000000000000000000000000000000000C8D0D4C8D0D4C8D0D4C8D0D4
              0000C8D0D4C8D0D4000000FFFF00FFFF00FFFF00FFFF00FFFF00FFFF00FFFF00
              FFFF00FFFF00000000C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0
              D4C8D0D40000C8D0D4C8D0D4000000FFFF00FFFF00FFFF00FFFF00FFFF00FFFF
              00FFFF00FFFF00FFFF80000000C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8
              D0D4C8D0D4C8D0D40000C8D0D4C8D0D4000000FFFF00FFFF00FFFF0000000000
              0000000000000000000000000000000000C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4
              C8D0D4C8D0D4C8D0D4C8D0D40000C8D0D4C8D0D4C8D0D4000000000000000000
              C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D48000008000008000
              00C8D0D4C8D0D4C8D0D4C8D0D4C8D0D40000C8D0D4C8D0D4C8D0D4C8D0D4C8D0
              D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D480
              0000800000C8D0D4C8D0D4C8D0D4C8D0D4C8D0D40000C8D0D4C8D0D4C8D0D4C8
              D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4800000C8D0D4C8D0D4C8D0D4
              800000C8D0D4800000C8D0D4C8D0D4C8D0D4C8D0D4C8D0D40000C8D0D4C8D0D4
              C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D48000008000
              00800000C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D40000C8D0
              D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8
              D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4
              0000C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4
              C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0
              D4C8D0D40000C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0
              D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8
              D0D4C8D0D4C8D0D40000C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8
              D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4C8D0D4
              C8D0D4C8D0D4C8D0D4C8D0D40000}
            OnClick = SpeedButton1Click
          end
          object Label18: TLabel
            Left = 16
            Top = 48
            Width = 212
            Height = 13
            Caption = 'Path to default HTML/CSS editor executable'
          end
          object Panel7: TPanel
            Left = 1
            Top = 320
            Width = 532
            Height = 41
            Align = alBottom
            BevelOuter = bvNone
            TabOrder = 0
          end
          object _editor: TEdit
            Left = 16
            Top = 72
            Width = 437
            Height = 21
            Anchors = [akLeft, akTop, akRight]
            ReadOnly = True
            TabOrder = 1
          end
        end
      end
    end
    object settings_head: TPanel
      Left = 1
      Top = 1
      Width = 542
      Height = 41
      Align = alTop
      Alignment = taLeftJustify
      Caption = '   Settings'
      Color = clWhite
      Font.Charset = DEFAULT_CHARSET
      Font.Color = clWindowText
      Font.Height = -11
      Font.Name = 'MS Sans Serif'
      Font.Style = [fsBold]
      ParentFont = False
      TabOrder = 1
    end
  end
  object editor_od: TOpenDialog
    Filter = 'Applications (*.exe)|*.exe|All files (*.*)|*.*'
    Left = 485
    Top = 9
  end
end
