object move_frm: Tmove_frm
  Left = 0
  Top = 0
  Width = 544
  Height = 433
  Font.Charset = DEFAULT_CHARSET
  Font.Color = clWindowText
  Font.Height = -11
  Font.Name = 'MS Sans Serif'
  Font.Style = []
  ParentFont = False
  TabOrder = 0
  object move_bkgr: TPanel
    Left = 0
    Top = 0
    Width = 544
    Height = 433
    Align = alClient
    BevelOuter = bvLowered
    TabOrder = 0
    object Panel1: TPanel
      Left = 1
      Top = 1
      Width = 542
      Height = 41
      Align = alTop
      Alignment = taLeftJustify
      Caption = '   Move shop'
      Color = clWhite
      Font.Charset = DEFAULT_CHARSET
      Font.Color = clWindowText
      Font.Height = -11
      Font.Name = 'MS Sans Serif'
      Font.Style = [fsBold]
      ParentFont = False
      TabOrder = 0
    end
    object GroupBox1: TGroupBox
      Left = 1
      Top = 42
      Width = 542
      Height = 135
      Align = alTop
      Caption = 'Source shop'
      TabOrder = 1
      object src: TComboBox
        Left = 24
        Top = 48
        Width = 305
        Height = 21
        Style = csDropDownList
        ItemHeight = 13
        TabOrder = 0
        OnChange = srcChange
      end
    end
    object Panel2: TPanel
      Left = 1
      Top = 391
      Width = 542
      Height = 41
      Align = alBottom
      TabOrder = 2
      object move: TButton
        Left = 8
        Top = 8
        Width = 75
        Height = 25
        Caption = 'Move shop'
        TabOrder = 0
        OnClick = moveClick
      end
    end
    object shop_to: TGroupBox
      Left = 1
      Top = 177
      Width = 542
      Height = 214
      Align = alClient
      Caption = 'Destination shop'
      TabOrder = 3
      object dst: TComboBox
        Left = 24
        Top = 48
        Width = 305
        Height = 21
        Style = csDropDownList
        ItemHeight = 13
        TabOrder = 0
        OnChange = dstChange
      end
    end
  end
end
