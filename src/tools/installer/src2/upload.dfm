object f_upload: Tf_upload
  Left = 0
  Top = 0
  Width = 440
  Height = 305
  AutoScroll = False
  TabOrder = 0
  object FileName: TLabel
    Left = 16
    Top = 200
    Width = 3
    Height = 13
    Anchors = [akLeft, akBottom]
  end
  object Panel1: TPanel
    Left = 0
    Top = 264
    Width = 440
    Height = 41
    Align = alBottom
    BevelOuter = bvNone
    TabOrder = 0
    object _cancel: TButton
      Left = 352
      Top = 8
      Width = 75
      Height = 25
      Anchors = [akTop, akRight]
      Caption = 'Cancel'
      TabOrder = 0
      OnClick = _cancelClick
    end
  end
  object total_progress: TProgressBar
    Left = 16
    Top = 232
    Width = 409
    Height = 16
    Anchors = [akLeft, akRight, akBottom]
    Min = 0
    Max = 100
    TabOrder = 1
  end
  object file_progress: TProgressBar
    Left = 16
    Top = 192
    Width = 409
    Height = 16
    Anchors = [akLeft, akRight, akBottom]
    Min = 0
    Max = 100
    TabOrder = 2
    Visible = False
  end
  object Panel2: TPanel
    Left = 0
    Top = 0
    Width = 440
    Height = 97
    Align = alTop
    Alignment = taLeftJustify
    BevelOuter = bvNone
    Caption = '    Uploading files, please wait...'
    Font.Charset = DEFAULT_CHARSET
    Font.Color = clWindowText
    Font.Height = -11
    Font.Name = 'MS Sans Serif'
    Font.Style = [fsBold]
    ParentFont = False
    TabOrder = 3
  end
end
