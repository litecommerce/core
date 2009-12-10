object owerwrite: Towerwrite
  Left = 245
  Top = 227
  BorderIcons = []
  BorderStyle = bsSingle
  Caption = '   File already exists'
  ClientHeight = 91
  ClientWidth = 350
  Color = clBtnFace
  Font.Charset = DEFAULT_CHARSET
  Font.Color = clWindowText
  Font.Height = -11
  Font.Name = 'MS Sans Serif'
  Font.Style = []
  OldCreateOrder = False
  OnCreate = FormCreate
  PixelsPerInch = 96
  TextHeight = 13
  object Label1: TLabel
    Left = 104
    Top = 16
    Width = 141
    Height = 13
    Caption = 'File already exists. Owerwrite?'
  end
  object Image1: TImage
    Left = 24
    Top = 8
    Width = 31
    Height = 32
    AutoSize = True
    Picture.Data = {
      07544269746D617076020000424D760200000000000076000000280000001F00
      000020000000010004000000000000020000C40E0000C40E0000100000000000
      0000000000000000800000800000008080008000000080008000808000008080
      8000C0C0C0000000FF0000FF000000FFFF00FF000000FF00FF00FFFF0000FFFF
      FF00666666666666666666776666666666606666666666666666677766666666
      6660666666666666666600776666666666606666666666666660F07766666666
      6660666666666666660FF07766666666666066666666666670FFF07766666666
      666066666666677770FFF07777766666666066666667770008FFF07777777666
      666066666670008FFFFFF80007777766666066666008FFFFFFFFFFFF80077776
      6660666608FFFFFFFFFFFFFFFF80777766606660FFFFFFFFFFFFFFFFFFFF0777
      7660660FFFFFFFFF8CC8FFFFFFFFF077776067FFFFFFFFFFCCCCFFFFFFFFFF07
      776078FFFFFFFFFFCCCCFFFFFFFFFF8077707FFFFFFFFFFF8CC8FFFFFFFFFFF0
      77708FFFFFFFFFFFFFFFFFFFFFFFFFF80770FFFFFFFFFFFFFCCFFFFFFFFFFFFF
      0770FFFFFFFFFFFFFCC8FFFFFFFFFFFF0770FFFFFFFFFFFFFCCCFFFFFFFFFFFF
      0770FFFFFFFFFFFFF8CCCFFFFFFFFFFF0770FFFFFFFFF8CC8FCCCCFFFFFFFFFF
      07608FFFFFFFFCCCCF8CCCCFFFFFFFF807607FFFFFFFFCCCCFFCCCCFFFFFFFF0
      766078FFFFFFFCCFFFFCCCCFFFFFFF80666067FFFFFFF8C8FFCCCC8FFFFFFF06
      6660667FFFFFFF8CCCCCC8FFFFFFF06666606667FFFFFFFFFFFFFFFFFFFF0666
      6660666678FFFFFFFFFFFFFFFF876666666066666778FFFFFFFFFFFF87766666
      666066666667778FFFFFF8777666666666606666666666777777776666666666
      6660}
    Transparent = True
  end
  object Panel1: TPanel
    Left = 0
    Top = 50
    Width = 350
    Height = 41
    Align = alBottom
    BevelOuter = bvNone
    TabOrder = 0
    object _owerwite: TButton
      Left = 96
      Top = 8
      Width = 75
      Height = 25
      Caption = 'Yes'
      TabOrder = 1
      OnClick = _owerwiteClick
    end
    object _skip: TButton
      Left = 16
      Top = 8
      Width = 75
      Height = 25
      Caption = 'No'
      TabOrder = 0
      OnClick = _skipClick
    end
    object _qwerwrite_all: TButton
      Left = 176
      Top = 8
      Width = 75
      Height = 25
      Caption = 'Yes to all'
      TabOrder = 2
      OnClick = _qwerwrite_allClick
    end
    object _cancel: TButton
      Left = 256
      Top = 8
      Width = 75
      Height = 25
      Caption = 'Cancel'
      TabOrder = 3
      OnClick = _cancelClick
    end
  end
end
