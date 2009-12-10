object backup_frm: Tbackup_frm
  Left = 0
  Top = 0
  Width = 544
  Height = 433
  HorzScrollBar.Smooth = True
  HorzScrollBar.Style = ssFlat
  TabOrder = 0
  object Label2: TLabel
    Left = 168
    Top = 200
    Width = 180
    Height = 13
    Caption = 'Choose database backup (*.SQL files)'
  end
  object backup_bkgr: TPanel
    Left = 0
    Top = 0
    Width = 544
    Height = 433
    Align = alClient
    BevelOuter = bvLowered
    TabOrder = 0
    object backup_info_panel: TPanel
      Left = 1
      Top = 1
      Width = 542
      Height = 40
      Align = alTop
      Alignment = taLeftJustify
      Caption = '   Backup/Restore'
      Color = clWhite
      Font.Charset = DEFAULT_CHARSET
      Font.Color = clWindowText
      Font.Height = -11
      Font.Name = 'MS Sans Serif'
      Font.Style = [fsBold]
      ParentFont = False
      TabOrder = 0
    end
    object backup_shop: TGroupBox
      Left = 1
      Top = 65
      Width = 542
      Height = 45
      Align = alTop
      Caption = 'Select shop'
      TabOrder = 1
      object backup_choose_shop: TComboBox
        Left = 24
        Top = 15
        Width = 289
        Height = 21
        Style = csDropDownList
        ItemHeight = 13
        TabOrder = 0
        OnChange = backup_choose_shopChange
      end
    end
    object PageControl1: TPageControl
      Left = 1
      Top = 119
      Width = 542
      Height = 313
      ActivePage = TabSheet1
      Align = alClient
      TabOrder = 2
      object TabSheet1: TTabSheet
        Caption = 'Database'
        object GroupBox1: TGroupBox
          Left = 257
          Top = 0
          Width = 277
          Height = 285
          Align = alClient
          Caption = 'Restore'
          TabOrder = 0
          object Label1: TLabel
            Left = 10
            Top = 138
            Width = 175
            Height = 13
            Anchors = [akLeft, akBottom]
            Caption = 'Choose database backup (*.SQL file)'
          end
          object restore_sql_open: TSpeedButton
            Left = 210
            Top = 158
            Width = 23
            Height = 22
            Anchors = [akLeft, akBottom]
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
            OnClick = restore_sql_openClick
          end
          object restore_sql_name: TEdit
            Left = 14
            Top = 158
            Width = 190
            Height = 21
            Anchors = [akLeft, akBottom]
            TabOrder = 0
            OnChange = restore_sql_nameChange
          end
          object btn_db_restore: TButton
            Left = 14
            Top = 237
            Width = 140
            Height = 25
            Anchors = [akLeft, akBottom]
            Caption = 'Restore'
            Enabled = False
            TabOrder = 1
            OnClick = btn_db_restoreClick
          end
        end
        object GroupBox2: TGroupBox
          Left = 0
          Top = 0
          Width = 257
          Height = 285
          Align = alLeft
          Caption = 'Backup'
          TabOrder = 1
          object btn_db_backup: TButton
            Left = 105
            Top = 237
            Width = 140
            Height = 25
            Anchors = [akLeft, akBottom]
            Caption = 'Backup'
            TabOrder = 0
            OnClick = btn_db_backupClick
          end
          object Memo2: TMemo
            Left = 8
            Top = 24
            Width = 241
            Height = 97
            TabStop = False
            BorderStyle = bsNone
            Color = clBtnFace
            Lines.Strings = (
              'Backup your shop database. The database '
              'contains information about your products, '
              'orders, customers etc. '
              ''
              'It'#39's recommended to backup your database on '
              'a regular basis.')
            ReadOnly = True
            TabOrder = 1
            WantReturns = False
          end
        end
      end
      object TabSheet2: TTabSheet
        Caption = 'Shop'
        ImageIndex = 1
        object GroupBox3: TGroupBox
          Left = 257
          Top = 0
          Width = 277
          Height = 285
          Align = alClient
          Caption = 'Restore'
          TabOrder = 0
          object restore_arx_open: TSpeedButton
            Left = 210
            Top = 158
            Width = 23
            Height = 22
            Anchors = [akLeft, akBottom]
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
            OnClick = restore_arx_openClick
          end
          object Label3: TLabel
            Left = 10
            Top = 138
            Width = 155
            Height = 13
            Anchors = [akLeft, akBottom]
            Caption = 'Choose shop backup (*.TAR file)'
          end
          object restore_arx_name: TEdit
            Left = 14
            Top = 158
            Width = 190
            Height = 21
            Anchors = [akLeft, akBottom]
            TabOrder = 0
            OnChange = restore_arx_nameChange
          end
          object btn_full_restore: TButton
            Left = 14
            Top = 237
            Width = 140
            Height = 25
            Anchors = [akLeft, akBottom]
            Caption = 'Restore'
            Enabled = False
            TabOrder = 1
            OnClick = btn_full_restoreClick
          end
          object modify_config: TCheckBox
            Left = 13
            Top = 185
            Width = 209
            Height = 17
            Anchors = [akLeft, akBottom]
            Caption = 'Update information in configuration files'
            TabOrder = 2
          end
          object restore_defaults: TCheckBox
            Left = 13
            Top = 206
            Width = 212
            Height = 17
            Anchors = [akLeft, akBottom]
            Caption = 'Restore default UNIX permissions'
            TabOrder = 3
          end
        end
        object GroupBox4: TGroupBox
          Left = 0
          Top = 0
          Width = 257
          Height = 285
          Align = alLeft
          Caption = 'Backup'
          TabOrder = 1
          object btn_full_backup: TButton
            Left = 105
            Top = 237
            Width = 140
            Height = 25
            Anchors = [akLeft, akBottom]
            Caption = 'Backup'
            TabOrder = 0
            OnClick = btn_full_backupClick
          end
          object sql_include: TCheckBox
            Left = 9
            Top = 186
            Width = 224
            Height = 17
            Anchors = [akLeft, akBottom]
            Caption = 'Include database dump in backup'
            TabOrder = 1
          end
          object Memo1: TMemo
            Left = 8
            Top = 24
            Width = 241
            Height = 97
            TabStop = False
            BorderStyle = bsNone
            Color = clBtnFace
            Lines.Strings = (
              'Backup entire shop. Backup all scripts and '
              'templates of your shop. It is possible to include '
              'database dump in your backup so you can have '
              'entire snapshot of your shop in one file. Do no '
              'forget to backup your shop when you make any '
              'changes in scripts or design.')
            ReadOnly = True
            TabOrder = 2
            WantReturns = False
          end
        end
      end
    end
    object Panel1: TPanel
      Left = 1
      Top = 41
      Width = 542
      Height = 24
      Align = alTop
      BevelOuter = bvNone
      TabOrder = 3
    end
    object Panel2: TPanel
      Left = 1
      Top = 110
      Width = 542
      Height = 9
      Align = alTop
      BevelOuter = bvNone
      TabOrder = 4
    end
  end
  object save_sback_file: TSaveDialog
    Filter = 'Shop archive (*.tar)|*.tar|All files|*.*'
    Options = [ofOverwritePrompt, ofHideReadOnly, ofEnableSizing]
    Left = 345
    Top = 10
  end
  object restore_sql_od: TOpenDialog
    Filter = 'SQL dump file (*.sql)|*.sql|All files|*.*'
    Left = 425
    Top = 10
  end
  object restore_arx_o_dialog: TOpenDialog
    Filter = 'Shop archive (*.tar)|*.tar|All files|*.*'
    Left = 465
    Top = 10
  end
  object save_dback_file: TSaveDialog
    Filter = 'SQL dump file (*.sql)|*.sql|All files|*.*'
    Options = [ofOverwritePrompt, ofHideReadOnly, ofEnableSizing]
    Left = 385
    Top = 10
  end
end
