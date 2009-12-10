object f_splash: Tf_splash
  Left = 0
  Top = 0
  Width = 419
  Height = 312
  AutoScroll = False
  TabOrder = 0
  object Shape1: TShape
    Left = 0
    Top = 0
    Width = 419
    Height = 271
    Align = alClient
    Pen.Color = clWhite
  end
  object Label1: TLabel
    Left = 0
    Top = 56
    Width = 419
    Height = 13
    Alignment = taCenter
    AutoSize = False
    Caption = 'Welcome to LiteCommerce installer'
    Font.Charset = DEFAULT_CHARSET
    Font.Color = clWindowText
    Font.Height = -11
    Font.Name = 'MS Sans Serif'
    Font.Style = [fsBold]
    ParentFont = False
    Transparent = True
  end
  object Label2: TLabel
    Left = 0
    Top = 90
    Width = 419
    Height = 13
    Alignment = taCenter
    AutoSize = False
    Caption = 'Thank you for choosing LiteCommerce'
    Transparent = True
  end
  object Label3: TLabel
    Left = 0
    Top = 114
    Width = 419
    Height = 13
    Alignment = taCenter
    AutoSize = False
    Caption = 'This wizard will provide you with the installation instructions'
    Transparent = True
  end
  object Label4: TLabel
    Left = 0
    Top = 126
    Width = 419
    Height = 17
    Alignment = taCenter
    AutoSize = False
    Caption = 'and will handle most of the installation tasks for you'
    Transparent = True
  end
  object Panel1: TPanel
    Left = 0
    Top = 271
    Width = 419
    Height = 41
    Align = alBottom
    BevelOuter = bvNone
    TabOrder = 0
    object _cancel: TButton
      Left = 339
      Top = 8
      Width = 75
      Height = 25
      Anchors = [akTop, akRight]
      Caption = 'Cancel'
      TabOrder = 1
      OnClick = _cancelClick
    end
    object _continue: TButton
      Left = 259
      Top = 8
      Width = 75
      Height = 25
      Anchors = [akTop, akRight]
      Caption = 'Continue'
      TabOrder = 0
      OnClick = _continueClick
    end
  end
end
