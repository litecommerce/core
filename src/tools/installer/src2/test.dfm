object f_test: Tf_test
  Left = 0
  Top = 0
  Width = 402
  Height = 319
  AutoScroll = False
  TabOrder = 0
  object Panel1: TPanel
    Left = 0
    Top = 276
    Width = 402
    Height = 43
    Align = alBottom
    BevelOuter = bvNone
    TabOrder = 0
    object _cancel: TButton
      Left = 320
      Top = 8
      Width = 75
      Height = 25
      Anchors = [akTop, akRight]
      Caption = 'Cancel'
      TabOrder = 2
      OnClick = _cancelClick
    end
    object _continue: TButton
      Left = 240
      Top = 8
      Width = 75
      Height = 25
      Anchors = [akTop, akRight]
      Caption = 'Continue'
      Default = True
      Enabled = False
      TabOrder = 0
      OnClick = _continueClick
    end
    object _back: TButton
      Left = 160
      Top = 8
      Width = 75
      Height = 25
      Anchors = [akTop, akRight]
      Caption = 'Back'
      Enabled = False
      TabOrder = 1
      OnClick = _backClick
    end
  end
  object display: TMemo
    Left = 0
    Top = 0
    Width = 402
    Height = 276
    Align = alClient
    ReadOnly = True
    ScrollBars = ssVertical
    TabOrder = 1
  end
end
