object f_extract: Tf_extract
  Left = 0
  Top = 0
  Width = 472
  Height = 232
  AutoScroll = False
  Color = clBtnFace
  ParentColor = False
  TabOrder = 0
  object FileName: TLabel
    Left = 16
    Top = 117
    Width = 75
    Height = 13
    Anchors = [akLeft, akBottom]
    Caption = 'Extracting file ...'
    Transparent = True
  end
  object Panel1: TPanel
    Left = 0
    Top = 191
    Width = 472
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
      TabOrder = 0
      OnClick = _cancelClick
    end
  end
  object FilesProgress: TProgressBar
    Left = 16
    Top = 158
    Width = 441
    Height = 16
    Anchors = [akLeft, akRight, akBottom]
    Min = 0
    Max = 100
    TabOrder = 1
  end
  object Head: TPanel
    Left = 0
    Top = 0
    Width = 472
    Height = 97
    Align = alTop
    Alignment = taLeftJustify
    BevelOuter = bvNone
    Caption = '    Extracting files, please wait...'
    Font.Charset = DEFAULT_CHARSET
    Font.Color = clWindowText
    Font.Height = -11
    Font.Name = 'MS Sans Serif'
    Font.Style = [fsBold]
    ParentFont = False
    TabOrder = 2
  end
end
