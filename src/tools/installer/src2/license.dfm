object f_license: Tf_license
  Left = 0
  Top = 0
  Width = 424
  Height = 371
  AutoScroll = False
  TabOrder = 0
  object Panel1: TPanel
    Left = 0
    Top = 330
    Width = 424
    Height = 41
    Align = alBottom
    BevelOuter = bvNone
    TabOrder = 0
    object _cancel: TButton
      Left = 336
      Top = 8
      Width = 75
      Height = 25
      Anchors = [akTop, akRight]
      Caption = 'Cancel'
      TabOrder = 2
      OnClick = _cancelClick
    end
    object _continue: TButton
      Left = 256
      Top = 8
      Width = 75
      Height = 25
      Anchors = [akTop, akRight]
      Caption = 'Continue..'
      Default = True
      Enabled = False
      TabOrder = 1
      OnClick = _continueClick
    end
    object _agree: TCheckBox
      Left = 16
      Top = 8
      Width = 193
      Height = 17
      Caption = 'I agree with terms and conditions'
      TabOrder = 0
      OnClick = _agreeClick
    end
  end
  object display: TMemo
    Left = 0
    Top = 0
    Width = 424
    Height = 330
    TabStop = False
    Align = alClient
    Font.Charset = RUSSIAN_CHARSET
    Font.Color = clWindowText
    Font.Height = -11
    Font.Name = 'Microsoft Sans Serif'
    Font.Style = []
    ParentFont = False
    ReadOnly = True
    ScrollBars = ssVertical
    TabOrder = 1
  end
end
