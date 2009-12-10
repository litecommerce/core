object f_completed: Tf_completed
  Left = 0
  Top = 0
  Width = 433
  Height = 240
  AutoScroll = False
  TabOrder = 0
  object Panel1: TPanel
    Left = 0
    Top = 199
    Width = 433
    Height = 41
    Align = alBottom
    BevelOuter = bvNone
    TabOrder = 0
    object _continue: TButton
      Left = 177
      Top = 8
      Width = 171
      Height = 25
      Anchors = [akTop, akRight]
      Caption = 'Continue with web installation..'
      Default = True
      TabOrder = 0
      OnClick = _continueClick
    end
    object _cancel: TButton
      Left = 353
      Top = 8
      Width = 75
      Height = 25
      Anchors = [akTop, akRight]
      Caption = 'Exit'
      TabOrder = 1
      OnClick = _cancelClick
    end
  end
  object _screen: TPanel
    Left = 0
    Top = 0
    Width = 433
    Height = 199
    Align = alClient
    BevelOuter = bvNone
    Color = clWhite
    TabOrder = 1
    object Label1: TLabel
      Left = 8
      Top = 56
      Width = 417
      Height = 49
      Anchors = [akLeft, akTop, akRight]
      AutoSize = False
      BiDiMode = bdRightToLeft
      Caption = 
        'All neccessary files were transferred to your web server and nec' +
        'cessary file permissions were set.'
      ParentBiDiMode = False
      Transparent = True
      WordWrap = True
    end
    object Label2: TLabel
      Left = 8
      Top = 88
      Width = 417
      Height = 49
      Anchors = [akLeft, akTop, akRight]
      AutoSize = False
      BiDiMode = bdRightToLeft
      Caption = 
        'Now installation will continue in web based installation wizard.' +
        ' Please click '#39'Continue web installation'#39' button to open your de' +
        'fault web browser and proceed to web part of the installation.'
      ParentBiDiMode = False
      Transparent = True
      WordWrap = True
    end
    object Label3: TLabel
      Left = 8
      Top = 24
      Width = 417
      Height = 13
      Alignment = taCenter
      Anchors = [akLeft, akTop, akRight]
      AutoSize = False
      Caption = 'Phase 1 of LiteCommerce installation is completed successfully.'
      Font.Charset = DEFAULT_CHARSET
      Font.Color = clWindowText
      Font.Height = -11
      Font.Name = 'MS Sans Serif'
      Font.Style = [fsBold]
      ParentFont = False
    end
    object Label4: TLabel
      Left = 8
      Top = 134
      Width = 417
      Height = 27
      AutoSize = False
      Caption = 
        'If for some reason your web browser does not start, launch your ' +
        'web browser manually and type this URL in the address line.'
      Transparent = True
      WordWrap = True
    end
    object Label5: TEdit
      Left = 8
      Top = 170
      Width = 417
      Height = 21
      BorderStyle = bsNone
      Font.Charset = DEFAULT_CHARSET
      Font.Color = clWindowText
      Font.Height = -11
      Font.Name = 'MS Sans Serif'
      Font.Style = [fsBold]
      ParentFont = False
      ReadOnly = True
      TabOrder = 0
      Text = 'Label5'
    end
  end
end
