// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdSSLOpenSSLHeaders.pas' rev: 5.00

#ifndef IdSSLOpenSSLHeadersHPP
#define IdSSLOpenSSLHeadersHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idsslopensslheaders
{
//-- type declarations -------------------------------------------------------
typedef int UInteger;

typedef int *PUInteger;

typedef void __fastcall (*PFunction)(void);

typedef int *PInteger;

typedef int *PLong;

typedef unsigned *PULong;

typedef System::Byte *PUShort;

typedef char * *PPChar;

typedef void *PSSL_CTX;

typedef void *PSSL;

typedef void *PSSL_METHOD;

typedef void *PSSL_SESSION;

typedef void * *PPSSL_SESSION;

typedef void *PSSL_CIPHER;

typedef void *Pevp_pkey_st;

typedef void *PSTACK;

typedef void * *PPSTACK;

typedef void *PCRYPTO_EX_DATA;

typedef void *PLHASH;

typedef void *PEVP_MD_CTX;

typedef void *PEVP_MD;

typedef void *PEVP_CIPHER;

typedef void *PEVP_CIPHER_CTX;

typedef void *PEVP_PKEY;

typedef void * *PPEVP_PKEY;

typedef void *PEVP_ENCODE_CTX;

typedef void *PX509_LOOKUP;

typedef void *PX509_STORE;

typedef void *PX509_STORE_CTX;

typedef void *PX509_CRL;

typedef void * *PPX509_CRL;

typedef void *PX509_LOOKUP_METHOD;

typedef void *PX509_NAME;

typedef void * *PPX509_NAME;

typedef void *PX509_OBJECT;

typedef void *PX509;

typedef void * *PPX509;

typedef void *PX509_EXTENSION_METHOD;

typedef void *PX509_REQ;

typedef void * *PPX509_REQ;

typedef void *PX509_ATTRIBUTE;

typedef void * *PPX509_ATTRIBUTE;

typedef void *PX509_EXTENSION;

typedef void * *PPX509_EXTENSION;

typedef void *PX509_NAME_ENTRY;

typedef void * *PPX509_NAME_ENTRY;

typedef void *PX509_ALGOR;

typedef void * *PPX509_ALGOR;

typedef void *PX509_VAL;

typedef void * *PPX509_VAL;

typedef void *PX509_PUBKEY;

typedef void * *PPX509_PUBKEY;

typedef void *PX509_SIG;

typedef void * *PPX509_SIG;

typedef void *PX509_REQ_INFO;

typedef void * *PPX509_REQ_INFO;

typedef void *PX509_CINF;

typedef void * *PPX509_CINF;

typedef void *PX509_REVOKED;

typedef void * *PPX509_REVOKED;

typedef void *PX509_CRL_INFO;

typedef void * *PPX509_CRL_INFO;

typedef void *PX509_PKEY;

typedef void * *PPX509_PKEY;

typedef void *PX509_INFO;

typedef void * *PPX509_INFO;

typedef void *PSTACK_X509_NAME;

typedef void *Ppem_password_cb;

typedef void *PPEM_ENCODE_SEAL_CTX;

typedef void *PSTACK_SSL_CIPHER;

typedef void *PSTACK_SSL_COMP;

typedef void *PSSL_COMP;

#pragma pack(push, 1)
struct ASN1_STRING
{
	int length;
	int _type;
	char *data;
	unsigned flags;
} ;
#pragma pack(pop)

typedef ASN1_STRING *PASN1_UTCTIME;

typedef ASN1_STRING  ASN1_UTCTIME;

typedef void *Phostent2;

typedef int time_t;

typedef System::Byte DES_cblock[8];

typedef System::Byte *PDES_cblock;

typedef System::Byte const_des_cblock[8];

typedef unsigned DES_LONG;

#pragma pack(push, 1)
struct des_cblock_union
{
	
	union
	{
		struct 
		{
			unsigned deslong[2];
			
		};
		struct 
		{
			System::Byte cblock[8];
			
		};
		
	};
} ;
#pragma pack(pop)

#pragma pack(push, 1)
struct des_ks_struct
{
	des_cblock_union ks;
	int weak_key;
} ;
#pragma pack(pop)

typedef des_ks_struct des_key_schedule[16];

typedef int des_cblocks;

//-- var, const, procedure ---------------------------------------------------
static const Shortint OPENSSL_ASN1_F_A2D_ASN1_OBJECT = 0x64;
static const System::Byte OPENSSL_ASN1_F_A2I_ASN1_ENUMERATED = 0xec;
static const Shortint OPENSSL_ASN1_F_A2I_ASN1_INTEGER = 0x65;
static const Shortint OPENSSL_ASN1_F_A2I_ASN1_STRING = 0x66;
static const Shortint OPENSSL_ASN1_F_ASN1_COLLATE_PRIMITIVE = 0x67;
static const Shortint OPENSSL_ASN1_F_ASN1_D2I_BIO = 0x68;
static const Shortint OPENSSL_ASN1_F_ASN1_D2I_FP = 0x69;
static const Shortint OPENSSL_ASN1_F_ASN1_DUP = 0x6a;
static const System::Byte OPENSSL_ASN1_F_ASN1_ENUMERATED_SET = 0xe8;
static const System::Byte OPENSSL_ASN1_F_ASN1_ENUMERATED_TO_BN = 0xe9;
static const System::Byte OPENSSL_ASN1_F_ASN1_GENERALIZEDTIME_NEW = 0xde;
static const Shortint OPENSSL_ASN1_F_ASN1_GET_OBJECT = 0x6b;
static const Shortint OPENSSL_ASN1_F_ASN1_HEADER_NEW = 0x6c;
static const Shortint OPENSSL_ASN1_F_ASN1_I2D_BIO = 0x6d;
static const Shortint OPENSSL_ASN1_F_ASN1_I2D_FP = 0x6e;
static const Shortint OPENSSL_ASN1_F_ASN1_INTEGER_SET = 0x6f;
static const Shortint OPENSSL_ASN1_F_ASN1_INTEGER_TO_BN = 0x70;
static const Shortint OPENSSL_ASN1_F_ASN1_OBJECT_NEW = 0x71;
static const System::Byte OPENSSL_ASN1_F_ASN1_PACK_STRING = 0xf5;
static const System::Byte OPENSSL_ASN1_F_ASN1_PBE_SET = 0xfd;
static const System::Byte OPENSSL_ASN1_F_ASN1_SEQ_PACK = 0xf6;
static const System::Byte OPENSSL_ASN1_F_ASN1_SEQ_UNPACK = 0xf7;
static const Shortint OPENSSL_ASN1_F_ASN1_SIGN = 0x72;
static const Shortint OPENSSL_ASN1_F_ASN1_STRING_NEW = 0x73;
static const Shortint OPENSSL_ASN1_F_ASN1_STRING_TYPE_NEW = 0x74;
static const Shortint OPENSSL_ASN1_F_ASN1_TYPE_GET_INT_OCTETSTRING = 0x75;
static const Shortint OPENSSL_ASN1_F_ASN1_TYPE_GET_OCTETSTRING = 0x76;
static const Shortint OPENSSL_ASN1_F_ASN1_TYPE_NEW = 0x77;
static const System::Byte OPENSSL_ASN1_F_ASN1_UNPACK_STRING = 0xf8;
static const Shortint OPENSSL_ASN1_F_ASN1_UTCTIME_NEW = 0x78;
static const Shortint OPENSSL_ASN1_F_ASN1_VERIFY = 0x79;
static const System::Byte OPENSSL_ASN1_F_AUTHORITY_KEYID_NEW = 0xed;
static const System::Byte OPENSSL_ASN1_F_BASIC_CONSTRAINTS_NEW = 0xe2;
static const System::Byte OPENSSL_ASN1_F_BN_TO_ASN1_ENUMERATED = 0xea;
static const Shortint OPENSSL_ASN1_F_BN_TO_ASN1_INTEGER = 0x7a;
static const Shortint OPENSSL_ASN1_F_D2I_ASN1_BIT_STRING = 0x7b;
static const Shortint OPENSSL_ASN1_F_D2I_ASN1_BMPSTRING = 0x7c;
static const Shortint OPENSSL_ASN1_F_D2I_ASN1_BOOLEAN = 0x7d;
static const Shortint OPENSSL_ASN1_F_D2I_ASN1_BYTES = 0x7e;
static const System::Byte OPENSSL_ASN1_F_D2I_ASN1_ENUMERATED = 0xeb;
static const System::Byte OPENSSL_ASN1_F_D2I_ASN1_GENERALIZEDTIME = 0xdf;
static const Shortint OPENSSL_ASN1_F_D2I_ASN1_HEADER = 0x7f;
static const System::Byte OPENSSL_ASN1_F_D2I_ASN1_INTEGER = 0x80;
static const System::Byte OPENSSL_ASN1_F_D2I_ASN1_OBJECT = 0x81;
static const System::Byte OPENSSL_ASN1_F_D2I_ASN1_OCTET_STRING = 0x82;
static const System::Byte OPENSSL_ASN1_F_D2I_ASN1_PRINT_TYPE = 0x83;
static const System::Byte OPENSSL_ASN1_F_D2I_ASN1_SET = 0x84;
static const System::Byte OPENSSL_ASN1_F_D2I_ASN1_TIME = 0xe0;
static const System::Byte OPENSSL_ASN1_F_D2I_ASN1_TYPE = 0x85;
static const System::Byte OPENSSL_ASN1_F_D2I_ASN1_TYPE_BYTES = 0x86;
static const Word OPENSSL_ASN1_F_D2I_ASN1_UINTEGER = 0x118;
static const System::Byte OPENSSL_ASN1_F_D2I_ASN1_UTCTIME = 0x87;
static const Word OPENSSL_ASN1_F_D2I_ASN1_UTF8STRING = 0x10a;
static const Word OPENSSL_ASN1_F_D2I_ASN1_VISIBLESTRING = 0x10b;
static const System::Byte OPENSSL_ASN1_F_D2I_AUTHORITY_KEYID = 0xee;
static const System::Byte OPENSSL_ASN1_F_D2I_BASIC_CONSTRAINTS = 0xe3;
static const System::Byte OPENSSL_ASN1_F_D2I_DHPARAMS = 0x88;
static const Word OPENSSL_ASN1_F_D2I_DIST_POINT = 0x114;
static const Word OPENSSL_ASN1_F_D2I_DIST_POINT_NAME = 0x115;
static const System::Byte OPENSSL_ASN1_F_D2I_DSAPARAMS = 0x89;
static const System::Byte OPENSSL_ASN1_F_D2I_DSAPRIVATEKEY = 0x8a;
static const System::Byte OPENSSL_ASN1_F_D2I_DSAPUBLICKEY = 0x8b;
static const System::Byte OPENSSL_ASN1_F_D2I_GENERAL_NAME = 0xe6;
static const System::Byte OPENSSL_ASN1_F_D2I_NETSCAPE_CERT_SEQUENCE = 0xe4;
static const System::Byte OPENSSL_ASN1_F_D2I_NETSCAPE_PKEY = 0x8c;
static const System::Byte OPENSSL_ASN1_F_D2I_NETSCAPE_RSA = 0x8d;
static const System::Byte OPENSSL_ASN1_F_D2I_NETSCAPE_RSA_2 = 0x8e;
static const System::Byte OPENSSL_ASN1_F_D2I_NETSCAPE_SPKAC = 0x8f;
static const System::Byte OPENSSL_ASN1_F_D2I_NETSCAPE_SPKI = 0x90;
static const Word OPENSSL_ASN1_F_D2I_NOTICEREF = 0x10c;
static const Word OPENSSL_ASN1_F_D2I_PBE2PARAM = 0x106;
static const System::Byte OPENSSL_ASN1_F_D2I_PBEPARAM = 0xf9;
static const Word OPENSSL_ASN1_F_D2I_PBKDF2PARAM = 0x107;
static const System::Byte OPENSSL_ASN1_F_D2I_PKCS12 = 0xfe;
static const System::Byte OPENSSL_ASN1_F_D2I_PKCS12_BAGS = 0xff;
static const Word OPENSSL_ASN1_F_D2I_PKCS12_MAC_DATA = 0x100;
static const Word OPENSSL_ASN1_F_D2I_PKCS12_SAFEBAG = 0x101;
static const System::Byte OPENSSL_ASN1_F_D2I_PKCS7 = 0x91;
static const System::Byte OPENSSL_ASN1_F_D2I_PKCS7_DIGEST = 0x92;
static const System::Byte OPENSSL_ASN1_F_D2I_PKCS7_ENCRYPT = 0x93;
static const System::Byte OPENSSL_ASN1_F_D2I_PKCS7_ENC_CONTENT = 0x94;
static const System::Byte OPENSSL_ASN1_F_D2I_PKCS7_ENVELOPE = 0x95;
static const System::Byte OPENSSL_ASN1_F_D2I_PKCS7_ISSUER_AND_SERIAL = 0x96;
static const System::Byte OPENSSL_ASN1_F_D2I_PKCS7_RECIP_INFO = 0x97;
static const System::Byte OPENSSL_ASN1_F_D2I_PKCS7_SIGNED = 0x98;
static const System::Byte OPENSSL_ASN1_F_D2I_PKCS7_SIGNER_INFO = 0x99;
static const System::Byte OPENSSL_ASN1_F_D2I_PKCS7_SIGN_ENVELOPE = 0x9a;
static const System::Byte OPENSSL_ASN1_F_D2I_PKCS8_PRIV_KEY_INFO = 0xfa;
static const System::Byte OPENSSL_ASN1_F_D2I_PKEY_USAGE_PERIOD = 0xef;
static const Word OPENSSL_ASN1_F_D2I_POLICYINFO = 0x10d;
static const Word OPENSSL_ASN1_F_D2I_POLICYQUALINFO = 0x10e;
static const System::Byte OPENSSL_ASN1_F_D2I_PRIVATEKEY = 0x9b;
static const System::Byte OPENSSL_ASN1_F_D2I_PUBLICKEY = 0x9c;
static const System::Byte OPENSSL_ASN1_F_D2I_RSAPRIVATEKEY = 0x9d;
static const System::Byte OPENSSL_ASN1_F_D2I_RSAPUBLICKEY = 0x9e;
static const System::Byte OPENSSL_ASN1_F_D2I_SXNET = 0xf1;
static const System::Byte OPENSSL_ASN1_F_D2I_SXNETID = 0xf3;
static const Word OPENSSL_ASN1_F_D2I_USERNOTICE = 0x10f;
static const System::Byte OPENSSL_ASN1_F_D2I_X509 = 0x9f;
static const System::Byte OPENSSL_ASN1_F_D2I_X509_ALGOR = 0xa0;
static const System::Byte OPENSSL_ASN1_F_D2I_X509_ATTRIBUTE = 0xa1;
static const System::Byte OPENSSL_ASN1_F_D2I_X509_CINF = 0xa2;
static const System::Byte OPENSSL_ASN1_F_D2I_X509_CRL = 0xa3;
static const System::Byte OPENSSL_ASN1_F_D2I_X509_CRL_INFO = 0xa4;
static const System::Byte OPENSSL_ASN1_F_D2I_X509_EXTENSION = 0xa5;
static const System::Byte OPENSSL_ASN1_F_D2I_X509_KEY = 0xa6;
static const System::Byte OPENSSL_ASN1_F_D2I_X509_NAME = 0xa7;
static const System::Byte OPENSSL_ASN1_F_D2I_X509_NAME_ENTRY = 0xa8;
static const System::Byte OPENSSL_ASN1_F_D2I_X509_PKEY = 0xa9;
static const System::Byte OPENSSL_ASN1_F_D2I_X509_PUBKEY = 0xaa;
static const System::Byte OPENSSL_ASN1_F_D2I_X509_REQ = 0xab;
static const System::Byte OPENSSL_ASN1_F_D2I_X509_REQ_INFO = 0xac;
static const System::Byte OPENSSL_ASN1_F_D2I_X509_REVOKED = 0xad;
static const System::Byte OPENSSL_ASN1_F_D2I_X509_SIG = 0xae;
static const System::Byte OPENSSL_ASN1_F_D2I_X509_VAL = 0xaf;
static const Word OPENSSL_ASN1_F_DIST_POINT_NAME_NEW = 0x116;
static const Word OPENSSL_ASN1_F_DIST_POINT_NEW = 0x117;
static const System::Byte OPENSSL_ASN1_F_GENERAL_NAME_NEW = 0xe7;
static const System::Byte OPENSSL_ASN1_F_I2D_ASN1_HEADER = 0xb0;
static const System::Byte OPENSSL_ASN1_F_I2D_ASN1_TIME = 0xe1;
static const System::Byte OPENSSL_ASN1_F_I2D_DHPARAMS = 0xb1;
static const System::Byte OPENSSL_ASN1_F_I2D_DSAPARAMS = 0xb2;
static const System::Byte OPENSSL_ASN1_F_I2D_DSAPRIVATEKEY = 0xb3;
static const System::Byte OPENSSL_ASN1_F_I2D_DSAPUBLICKEY = 0xb4;
static const System::Byte OPENSSL_ASN1_F_I2D_NETSCAPE_RSA = 0xb5;
static const System::Byte OPENSSL_ASN1_F_I2D_PKCS7 = 0xb6;
static const System::Byte OPENSSL_ASN1_F_I2D_PRIVATEKEY = 0xb7;
static const System::Byte OPENSSL_ASN1_F_I2D_PUBLICKEY = 0xb8;
static const System::Byte OPENSSL_ASN1_F_I2D_RSAPRIVATEKEY = 0xb9;
static const System::Byte OPENSSL_ASN1_F_I2D_RSAPUBLICKEY = 0xba;
static const System::Byte OPENSSL_ASN1_F_I2D_X509_ATTRIBUTE = 0xbb;
static const System::Byte OPENSSL_ASN1_F_I2T_ASN1_OBJECT = 0xbc;
static const System::Byte OPENSSL_ASN1_F_NETSCAPE_CERT_SEQUENCE_NEW = 0xe5;
static const System::Byte OPENSSL_ASN1_F_NETSCAPE_PKEY_NEW = 0xbd;
static const System::Byte OPENSSL_ASN1_F_NETSCAPE_SPKAC_NEW = 0xbe;
static const System::Byte OPENSSL_ASN1_F_NETSCAPE_SPKI_NEW = 0xbf;
static const Word OPENSSL_ASN1_F_NOTICEREF_NEW = 0x110;
static const Word OPENSSL_ASN1_F_PBE2PARAM_NEW = 0x108;
static const System::Byte OPENSSL_ASN1_F_PBEPARAM_NEW = 0xfb;
static const Word OPENSSL_ASN1_F_PBKDF2PARAM_NEW = 0x109;
static const Word OPENSSL_ASN1_F_PKCS12_BAGS_NEW = 0x102;
static const Word OPENSSL_ASN1_F_PKCS12_MAC_DATA_NEW = 0x103;
static const Word OPENSSL_ASN1_F_PKCS12_NEW = 0x104;
static const Word OPENSSL_ASN1_F_PKCS12_SAFEBAG_NEW = 0x105;
static const Word OPENSSL_ASN1_F_PKCS5_PBE2_SET = 0x119;
static const System::Byte OPENSSL_ASN1_F_PKCS7_DIGEST_NEW = 0xc0;
static const System::Byte OPENSSL_ASN1_F_PKCS7_ENCRYPT_NEW = 0xc1;
static const System::Byte OPENSSL_ASN1_F_PKCS7_ENC_CONTENT_NEW = 0xc2;
static const System::Byte OPENSSL_ASN1_F_PKCS7_ENVELOPE_NEW = 0xc3;
static const System::Byte OPENSSL_ASN1_F_PKCS7_ISSUER_AND_SERIAL_NEW = 0xc4;
static const System::Byte OPENSSL_ASN1_F_PKCS7_NEW = 0xc5;
static const System::Byte OPENSSL_ASN1_F_PKCS7_RECIP_INFO_NEW = 0xc6;
static const System::Byte OPENSSL_ASN1_F_PKCS7_SIGNED_NEW = 0xc7;
static const System::Byte OPENSSL_ASN1_F_PKCS7_SIGNER_INFO_NEW = 0xc8;
static const System::Byte OPENSSL_ASN1_F_PKCS7_SIGN_ENVELOPE_NEW = 0xc9;
static const System::Byte OPENSSL_ASN1_F_PKCS8_PRIV_KEY_INFO_NEW = 0xfc;
static const System::Byte OPENSSL_ASN1_F_PKEY_USAGE_PERIOD_NEW = 0xf0;
static const Word OPENSSL_ASN1_F_POLICYINFO_NEW = 0x111;
static const Word OPENSSL_ASN1_F_POLICYQUALINFO_NEW = 0x112;
static const System::Byte OPENSSL_ASN1_F_SXNETID_NEW = 0xf4;
static const System::Byte OPENSSL_ASN1_F_SXNET_NEW = 0xf2;
static const Word OPENSSL_ASN1_F_USERNOTICE_NEW = 0x113;
static const System::Byte OPENSSL_ASN1_F_X509_ALGOR_NEW = 0xca;
static const System::Byte OPENSSL_ASN1_F_X509_ATTRIBUTE_NEW = 0xcb;
static const System::Byte OPENSSL_ASN1_F_X509_CINF_NEW = 0xcc;
static const System::Byte OPENSSL_ASN1_F_X509_CRL_INFO_NEW = 0xcd;
static const System::Byte OPENSSL_ASN1_F_X509_CRL_NEW = 0xce;
static const System::Byte OPENSSL_ASN1_F_X509_DHPARAMS_NEW = 0xcf;
static const System::Byte OPENSSL_ASN1_F_X509_EXTENSION_NEW = 0xd0;
static const System::Byte OPENSSL_ASN1_F_X509_INFO_NEW = 0xd1;
static const System::Byte OPENSSL_ASN1_F_X509_KEY_NEW = 0xd2;
static const System::Byte OPENSSL_ASN1_F_X509_NAME_ENTRY_NEW = 0xd3;
static const System::Byte OPENSSL_ASN1_F_X509_NAME_NEW = 0xd4;
static const System::Byte OPENSSL_ASN1_F_X509_NEW = 0xd5;
static const System::Byte OPENSSL_ASN1_F_X509_PKEY_NEW = 0xd6;
static const System::Byte OPENSSL_ASN1_F_X509_PUBKEY_NEW = 0xd7;
static const System::Byte OPENSSL_ASN1_F_X509_REQ_INFO_NEW = 0xd8;
static const System::Byte OPENSSL_ASN1_F_X509_REQ_NEW = 0xd9;
static const System::Byte OPENSSL_ASN1_F_X509_REVOKED_NEW = 0xda;
static const System::Byte OPENSSL_ASN1_F_X509_SIG_NEW = 0xdb;
static const System::Byte OPENSSL_ASN1_F_X509_VAL_FREE = 0xdc;
static const System::Byte OPENSSL_ASN1_F_X509_VAL_NEW = 0xdd;
static const Shortint OPENSSL_ASN1_OBJECT_FLAG_CRITICAL = 0x2;
static const Shortint OPENSSL_ASN1_OBJECT_FLAG_DYNAMIC = 0x1;
static const Shortint OPENSSL_ASN1_OBJECT_FLAG_DYNAMIC_DATA = 0x8;
static const Shortint OPENSSL_ASN1_OBJECT_FLAG_DYNAMIC_STRINGS = 0x4;
static const Shortint OPENSSL_ASN1_R_BAD_CLASS = 0x64;
static const Shortint OPENSSL_ASN1_R_BAD_OBJECT_HEADER = 0x65;
static const Shortint OPENSSL_ASN1_R_BAD_PASSWORD_READ = 0x66;
static const Shortint OPENSSL_ASN1_R_BAD_PKCS7_CONTENT = 0x67;
static const Shortint OPENSSL_ASN1_R_BAD_PKCS7_TYPE = 0x68;
static const Shortint OPENSSL_ASN1_R_BAD_TAG = 0x69;
static const Shortint OPENSSL_ASN1_R_BAD_TYPE = 0x6a;
static const Shortint OPENSSL_ASN1_R_BN_LIB = 0x6b;
static const Shortint OPENSSL_ASN1_R_BOOLEAN_IS_WRONG_LENGTH = 0x6c;
static const Shortint OPENSSL_ASN1_R_BUFFER_TOO_SMALL = 0x6d;
static const Shortint OPENSSL_ASN1_R_DATA_IS_WRONG = 0x6e;
static const System::Byte OPENSSL_ASN1_R_DECODE_ERROR = 0x9b;
static const Shortint OPENSSL_ASN1_R_DECODING_ERROR = 0x6f;
static const System::Byte OPENSSL_ASN1_R_ENCODE_ERROR = 0x9c;
static const Shortint OPENSSL_ASN1_R_ERROR_PARSING_SET_ELEMENT = 0x70;
static const System::Byte OPENSSL_ASN1_R_ERROR_SETTING_CIPHER_PARAMS = 0x9d;
static const System::Byte OPENSSL_ASN1_R_EXPECTING_AN_ENUMERATED = 0x9a;
static const Shortint OPENSSL_ASN1_R_EXPECTING_AN_INTEGER = 0x71;
static const Shortint OPENSSL_ASN1_R_EXPECTING_AN_OBJECT = 0x72;
static const Shortint OPENSSL_ASN1_R_EXPECTING_AN_OCTET_STRING = 0x73;
static const Shortint OPENSSL_ASN1_R_EXPECTING_A_BIT_STRING = 0x74;
static const Shortint OPENSSL_ASN1_R_EXPECTING_A_BOOLEAN = 0x75;
static const System::Byte OPENSSL_ASN1_R_EXPECTING_A_GENERALIZEDTIME = 0x97;
static const System::Byte OPENSSL_ASN1_R_EXPECTING_A_TIME = 0x98;
static const Shortint OPENSSL_ASN1_R_EXPECTING_A_UTCTIME = 0x76;
static const Shortint OPENSSL_ASN1_R_FIRST_NUM_TOO_LARGE = 0x77;
static const System::Byte OPENSSL_ASN1_R_GENERALIZEDTIME_TOO_LONG = 0x99;
static const Shortint OPENSSL_ASN1_R_HEADER_TOO_LONG = 0x78;
static const Shortint OPENSSL_ASN1_R_INVALID_DIGIT = 0x79;
static const Shortint OPENSSL_ASN1_R_INVALID_SEPARATOR = 0x7a;
static const Shortint OPENSSL_ASN1_R_INVALID_TIME_FORMAT = 0x7b;
static const Shortint OPENSSL_ASN1_R_IV_TOO_LARGE = 0x7c;
static const Shortint OPENSSL_ASN1_R_LENGTH_ERROR = 0x7d;
static const Shortint OPENSSL_ASN1_R_MISSING_SECOND_NUMBER = 0x7e;
static const Shortint OPENSSL_ASN1_R_NON_HEX_CHARACTERS = 0x7f;
static const System::Byte OPENSSL_ASN1_R_NOT_ENOUGH_DATA = 0x80;
static const System::Byte OPENSSL_ASN1_R_ODD_NUMBER_OF_CHARS = 0x81;
static const System::Byte OPENSSL_ASN1_R_PARSING = 0x82;
static const System::Byte OPENSSL_ASN1_R_PRIVATE_KEY_HEADER_MISSING = 0x83;
static const System::Byte OPENSSL_ASN1_R_SECOND_NUMBER_TOO_LARGE = 0x84;
static const System::Byte OPENSSL_ASN1_R_SHORT_LINE = 0x85;
static const System::Byte OPENSSL_ASN1_R_STRING_TOO_SHORT = 0x86;
static const System::Byte OPENSSL_ASN1_R_TAG_VALUE_TOO_HIGH = 0x87;
static const System::Byte OPENSSL_ASN1_R_THE_ASN1_OBJECT_IDENTIFIER_IS_NOT_KNOWN_FOR_THIS_MD = 0x88;
static const System::Byte OPENSSL_ASN1_R_TOO_LONG = 0x89;
static const System::Byte OPENSSL_ASN1_R_UNABLE_TO_DECODE_RSA_KEY = 0x8a;
static const System::Byte OPENSSL_ASN1_R_UNABLE_TO_DECODE_RSA_PRIVATE_KEY = 0x8b;
static const System::Byte OPENSSL_ASN1_R_UNKNOWN_ATTRIBUTE_TYPE = 0x8c;
static const System::Byte OPENSSL_ASN1_R_UNKNOWN_MESSAGE_DIGEST_ALGORITHM = 0x8d;
static const System::Byte OPENSSL_ASN1_R_UNKNOWN_OBJECT_TYPE = 0x8e;
static const System::Byte OPENSSL_ASN1_R_UNKNOWN_PUBLIC_KEY_TYPE = 0x8f;
static const System::Byte OPENSSL_ASN1_R_UNSUPPORTED_CIPHER = 0x90;
static const System::Byte OPENSSL_ASN1_R_UNSUPPORTED_ENCRYPTION_ALGORITHM = 0x91;
static const System::Byte OPENSSL_ASN1_R_UNSUPPORTED_PUBLIC_KEY_TYPE = 0x92;
static const System::Byte OPENSSL_ASN1_R_UTCTIME_TOO_LONG = 0x93;
static const System::Byte OPENSSL_ASN1_R_WRONG_PRINTABLE_TYPE = 0x94;
static const System::Byte OPENSSL_ASN1_R_WRONG_TAG = 0x95;
static const System::Byte OPENSSL_ASN1_R_WRONG_TYPE = 0x96;
static const Shortint OPENSSL_ASN1_STRING_FLAG_BITS_LEFT = 0x8;
static const Shortint OPENSSL_BF_BLOCK = 0x8;
static const Shortint OPENSSL_BF_DECRYPT = 0x0;
static const Shortint OPENSSL_BF_ENCRYPT = 0x1;
static const Shortint OPENSSL_BF_ROUNDS = 0x10;
static const Shortint OPENSSL_BIO_BIND_NORMAL = 0x0;
static const Shortint OPENSSL_BIO_BIND_REUSEADDR = 0x2;
static const Shortint OPENSSL_BIO_BIND_REUSEADDR_IF_UNUSED = 0x1;
static const Shortint OPENSSL_BIO_CB_CTRL = 0x6;
static const Shortint OPENSSL_BIO_CB_FREE = 0x1;
static const Shortint OPENSSL_BIO_CB_GETS = 0x5;
static const Shortint OPENSSL_BIO_CB_PUTS = 0x4;
static const Shortint OPENSSL_BIO_CB_READ = 0x2;
static const System::Byte OPENSSL_BIO_CB_RETURN = 0x80;
static const Shortint OPENSSL_BIO_CB_WRITE = 0x3;
static const Shortint OPENSSL_BIO_CLOSE = 0x1;
static const Shortint OPENSSL_BIO_CONN_S_BEFORE = 0x1;
static const Shortint OPENSSL_BIO_CONN_S_BLOCKED_CONNECT = 0x7;
static const Shortint OPENSSL_BIO_CONN_S_CONNECT = 0x5;
static const Shortint OPENSSL_BIO_CONN_S_CREATE_SOCKET = 0x4;
static const Shortint OPENSSL_BIO_CONN_S_GET_IP = 0x2;
static const Shortint OPENSSL_BIO_CONN_S_GET_PORT = 0x3;
static const Shortint OPENSSL_BIO_CONN_S_NBIO = 0x8;
static const Shortint OPENSSL_BIO_CONN_S_OK = 0x6;
static const Shortint OPENSSL_BIO_CTRL_DUP = 0xc;
static const Shortint OPENSSL_BIO_CTRL_EOF = 0x2;
static const Shortint OPENSSL_BIO_CTRL_FLUSH = 0xb;
static const Shortint OPENSSL_BIO_CTRL_GET = 0x5;
static const Shortint OPENSSL_BIO_CTRL_GET_CALLBACK = 0xf;
static const Shortint OPENSSL_BIO_CTRL_GET_CLOSE = 0x8;
static const Shortint OPENSSL_BIO_CTRL_INFO = 0x3;
static const Shortint OPENSSL_BIO_CTRL_PENDING = 0xa;
static const Shortint OPENSSL_BIO_CTRL_POP = 0x7;
static const Shortint OPENSSL_BIO_CTRL_PUSH = 0x6;
static const Shortint OPENSSL_BIO_CTRL_RESET = 0x1;
static const Shortint OPENSSL_BIO_CTRL_SET = 0x4;
static const Shortint OPENSSL_BIO_CTRL_SET_CALLBACK = 0xe;
static const Shortint OPENSSL_BIO_CTRL_SET_CLOSE = 0x9;
static const Shortint OPENSSL_BIO_CTRL_SET_FILENAME = 0x1e;
static const Shortint OPENSSL_BIO_CTRL_WPENDING = 0xd;
static const System::Byte OPENSSL_BIO_C_DESTROY_BIO_PAIR = 0x8b;
static const Shortint OPENSSL_BIO_C_DO_STATE_MACHINE = 0x65;
static const System::Byte OPENSSL_BIO_C_FILE_SEEK = 0x80;
static const System::Byte OPENSSL_BIO_C_FILE_TELL = 0x85;
static const Shortint OPENSSL_BIO_C_GET_ACCEPT = 0x7c;
static const System::Byte OPENSSL_BIO_C_GET_BIND_MODE = 0x84;
static const Shortint OPENSSL_BIO_C_GET_BUFF_NUM_LINES = 0x74;
static const Shortint OPENSSL_BIO_C_GET_BUF_MEM_PTR = 0x73;
static const System::Byte OPENSSL_BIO_C_GET_CIPHER_CTX = 0x81;
static const Shortint OPENSSL_BIO_C_GET_CIPHER_STATUS = 0x71;
static const Shortint OPENSSL_BIO_C_GET_CONNECT = 0x7b;
static const Shortint OPENSSL_BIO_C_GET_FD = 0x69;
static const Shortint OPENSSL_BIO_C_GET_FILE_PTR = 0x6b;
static const Shortint OPENSSL_BIO_C_GET_MD = 0x70;
static const Shortint OPENSSL_BIO_C_GET_MD_CTX = 0x78;
static const Shortint OPENSSL_BIO_C_GET_PROXY_PARAM = 0x79;
static const System::Byte OPENSSL_BIO_C_GET_READ_REQUEST = 0x8d;
static const System::Byte OPENSSL_BIO_C_GET_SOCKS = 0x86;
static const Shortint OPENSSL_BIO_C_GET_SSL = 0x6e;
static const Shortint OPENSSL_BIO_C_GET_SSL_NUM_RENEGOTIATES = 0x7e;
static const System::Byte OPENSSL_BIO_C_GET_WRITE_BUF_SIZE = 0x89;
static const System::Byte OPENSSL_BIO_C_GET_WRITE_GUARANTEE = 0x8c;
static const System::Byte OPENSSL_BIO_C_MAKE_BIO_PAIR = 0x8a;
static const Shortint OPENSSL_BIO_C_SET_ACCEPT = 0x76;
static const System::Byte OPENSSL_BIO_C_SET_BIND_MODE = 0x83;
static const Shortint OPENSSL_BIO_C_SET_BUFF_READ_DATA = 0x7a;
static const Shortint OPENSSL_BIO_C_SET_BUFF_SIZE = 0x75;
static const Shortint OPENSSL_BIO_C_SET_BUF_MEM = 0x72;
static const System::Byte OPENSSL_BIO_C_SET_BUF_MEM_EOF_RETURN = 0x82;
static const Shortint OPENSSL_BIO_C_SET_CONNECT = 0x64;
static const Shortint OPENSSL_BIO_C_SET_FD = 0x68;
static const Shortint OPENSSL_BIO_C_SET_FILENAME = 0x6c;
static const Shortint OPENSSL_BIO_C_SET_FILE_PTR = 0x6a;
static const Shortint OPENSSL_BIO_C_SET_MD = 0x6f;
static const Shortint OPENSSL_BIO_C_SET_NBIO = 0x66;
static const Shortint OPENSSL_BIO_C_SET_PROXY_PARAM = 0x67;
static const System::Byte OPENSSL_BIO_C_SET_SOCKS = 0x87;
static const Shortint OPENSSL_BIO_C_SET_SSL = 0x6d;
static const Shortint OPENSSL_BIO_C_SET_SSL_RENEGOTIATE_BYTES = 0x7d;
static const Shortint OPENSSL_BIO_C_SET_SSL_RENEGOTIATE_TIMEOUT = 0x7f;
static const System::Byte OPENSSL_BIO_C_SET_WRITE_BUF_SIZE = 0x88;
static const System::Byte OPENSSL_BIO_C_SHUTDOWN_WR = 0x8e;
static const Shortint OPENSSL_BIO_C_SSL_MODE = 0x77;
static const Word OPENSSL_BIO_FLAGS_BASE64_NO_NL = 0x100;
static const Shortint OPENSSL_BIO_FLAGS_IO_SPECIAL = 0x4;
static const Shortint OPENSSL_BIO_FLAGS_READ = 0x1;
static const Shortint OPENSSL_BIO_FLAGS_WRITE = 0x2;
static const Shortint OPENSSL_BIO_FLAGS_RWS = 0x7;
static const Shortint OPENSSL_BIO_FLAGS_SHOULD_RETRY = 0x8;
static const Shortint OPENSSL_BIO_FP_APPEND = 0x8;
static const Shortint OPENSSL_BIO_FP_READ = 0x2;
static const Shortint OPENSSL_BIO_FP_TEXT = 0x10;
static const Shortint OPENSSL_BIO_FP_WRITE = 0x4;
static const Shortint OPENSSL_BIO_F_ACPT_STATE = 0x64;
static const Shortint OPENSSL_BIO_F_BIO_ACCEPT = 0x65;
static const Shortint OPENSSL_BIO_F_BIO_BER_GET_HEADER = 0x66;
static const Shortint OPENSSL_BIO_F_BIO_CTRL = 0x67;
static const Shortint OPENSSL_BIO_F_BIO_GETHOSTBYNAME = 0x78;
static const Shortint OPENSSL_BIO_F_BIO_GETS = 0x68;
static const Shortint OPENSSL_BIO_F_BIO_GET_ACCEPT_SOCKET = 0x69;
static const Shortint OPENSSL_BIO_F_BIO_GET_HOST_IP = 0x6a;
static const Shortint OPENSSL_BIO_F_BIO_GET_PORT = 0x6b;
static const Shortint OPENSSL_BIO_F_BIO_MAKE_PAIR = 0x79;
static const Shortint OPENSSL_BIO_F_BIO_NEW = 0x6c;
static const Shortint OPENSSL_BIO_F_BIO_NEW_FILE = 0x6d;
static const Shortint OPENSSL_BIO_F_BIO_PUTS = 0x6e;
static const Shortint OPENSSL_BIO_F_BIO_READ = 0x6f;
static const Shortint OPENSSL_BIO_F_BIO_SOCK_INIT = 0x70;
static const Shortint OPENSSL_BIO_F_BIO_WRITE = 0x71;
static const Shortint OPENSSL_BIO_F_BUFFER_CTRL = 0x72;
static const Shortint OPENSSL_BIO_F_CONN_STATE = 0x73;
static const Shortint OPENSSL_BIO_F_FILE_CTRL = 0x74;
static const Shortint OPENSSL_BIO_F_MEM_WRITE = 0x75;
static const Shortint OPENSSL_BIO_F_SSL_NEW = 0x76;
static const Shortint OPENSSL_BIO_F_WSASTARTUP = 0x77;
static const Shortint OPENSSL_BIO_GHBN_CTRL_CACHE_SIZE = 0x3;
static const Shortint OPENSSL_BIO_GHBN_CTRL_FLUSH = 0x5;
static const Shortint OPENSSL_BIO_GHBN_CTRL_GET_ENTRY = 0x4;
static const Shortint OPENSSL_BIO_GHBN_CTRL_HITS = 0x1;
static const Shortint OPENSSL_BIO_GHBN_CTRL_MISSES = 0x2;
static const Shortint OPENSSL_BIO_NOCLOSE = 0x0;
static const Shortint OPENSSL_BIO_RR_CONNECT = 0x2;
static const Shortint OPENSSL_BIO_RR_SSL_X509_LOOKUP = 0x1;
static const Shortint OPENSSL_BIO_R_ACCEPT_ERROR = 0x64;
static const Shortint OPENSSL_BIO_R_BAD_FOPEN_MODE = 0x65;
static const Shortint OPENSSL_BIO_R_BAD_HOSTNAME_LOOKUP = 0x66;
static const Shortint OPENSSL_BIO_R_BROKEN_PIPE = 0x7c;
static const Shortint OPENSSL_BIO_R_CONNECT_ERROR = 0x67;
static const Shortint OPENSSL_BIO_R_ERROR_SETTING_NBIO = 0x68;
static const Shortint OPENSSL_BIO_R_ERROR_SETTING_NBIO_ON_ACCEPTED_SOCKET = 0x69;
static const Shortint OPENSSL_BIO_R_ERROR_SETTING_NBIO_ON_ACCEPT_SOCKET = 0x6a;
static const Shortint OPENSSL_BIO_R_GETHOSTBYNAME_ADDR_IS_NOT_AF_INET = 0x6b;
static const Shortint OPENSSL_BIO_R_INVALID_ARGUMENT = 0x7d;
static const Shortint OPENSSL_BIO_R_INVALID_IP_ADDRESS = 0x6c;
static const Shortint OPENSSL_BIO_R_IN_USE = 0x7b;
static const Shortint OPENSSL_BIO_R_KEEPALIVE = 0x6d;
static const Shortint OPENSSL_BIO_R_NBIO_CONNECT_ERROR = 0x6e;
static const Shortint OPENSSL_BIO_R_NO_ACCEPT_PORT_SPECIFIED = 0x6f;
static const Shortint OPENSSL_BIO_R_NO_HOSTNAME_SPECIFIED = 0x70;
static const Shortint OPENSSL_BIO_R_NO_PORT_DEFINED = 0x71;
static const Shortint OPENSSL_BIO_R_NO_PORT_SPECIFIED = 0x72;
static const Shortint OPENSSL_BIO_R_NULL_PARAMETER = 0x73;
static const Shortint OPENSSL_BIO_R_TAG_MISMATCH = 0x74;
static const Shortint OPENSSL_BIO_R_UNABLE_TO_BIND_SOCKET = 0x75;
static const Shortint OPENSSL_BIO_R_UNABLE_TO_CREATE_SOCKET = 0x76;
static const Shortint OPENSSL_BIO_R_UNABLE_TO_LISTEN_SOCKET = 0x77;
static const Shortint OPENSSL_BIO_R_UNINITIALIZED = 0x78;
static const Shortint OPENSSL_BIO_R_UNSUPPORTED_METHOD = 0x79;
static const Shortint OPENSSL_BIO_R_WSASTARTUP = 0x7a;
static const Word OPENSSL_BIO_TYPE_ACCEPT = 0x50d;
static const Word OPENSSL_BIO_TYPE_BASE64 = 0x20b;
static const Word OPENSSL_BIO_TYPE_BER = 0x212;
static const Word OPENSSL_BIO_TYPE_BIO = 0x413;
static const Word OPENSSL_BIO_TYPE_BUFFER = 0x209;
static const Word OPENSSL_BIO_TYPE_CIPHER = 0x20a;
static const Word OPENSSL_BIO_TYPE_CONNECT = 0x50c;
static const Word OPENSSL_BIO_TYPE_DESCRIPTOR = 0x100;
static const Word OPENSSL_BIO_TYPE_FD = 0x504;
static const Word OPENSSL_BIO_TYPE_FILE = 0x402;
static const Word OPENSSL_BIO_TYPE_FILTER = 0x200;
static const Word OPENSSL_BIO_TYPE_MD = 0x208;
static const Word OPENSSL_BIO_TYPE_MEM = 0x401;
static const Word OPENSSL_BIO_TYPE_NBIO_TEST = 0x210;
static const Shortint OPENSSL_BIO_TYPE_NONE = 0x0;
static const Word OPENSSL_BIO_TYPE_NULL = 0x406;
static const Word OPENSSL_BIO_TYPE_NULL_FILTER = 0x211;
static const Word OPENSSL_BIO_TYPE_PROXY_CLIENT = 0x20e;
static const Word OPENSSL_BIO_TYPE_PROXY_SERVER = 0x20f;
static const Word OPENSSL_BIO_TYPE_SOCKET = 0x505;
static const Word OPENSSL_BIO_TYPE_SOURCE_SINK = 0x400;
static const Word OPENSSL_BIO_TYPE_SSL = 0x207;
static const Shortint OPENSSL_BN_BITS = 0x40;
static const Shortint OPENSSL_BN_BITS2 = 0x20;
static const Shortint OPENSSL_BN_BITS4 = 0x10;
static const Shortint OPENSSL_BN_BYTES = 0x4;
static const Shortint OPENSSL_BN_CTX_NUM = 0xc;
#define OPENSSL_BN_DEC_FMT1 "%lu"
#define OPENSSL_BN_DEC_FMT2 "%09lu"
static const Shortint OPENSSL_BN_DEC_NUM = 0x9;
static const Word OPENSSL_BN_DEFAULT_BITS = 0x500;
static const Word OPENSSL_BN_FLG_FREE = 0x8000;
static const Shortint OPENSSL_BN_FLG_MALLOCED = 0x1;
static const Shortint OPENSSL_BN_FLG_STATIC_DATA = 0x2;
static const Shortint OPENSSL_BN_F_BN_BLINDING_CONVERT = 0x64;
static const Shortint OPENSSL_BN_F_BN_BLINDING_INVERT = 0x65;
static const Shortint OPENSSL_BN_F_BN_BLINDING_NEW = 0x66;
static const Shortint OPENSSL_BN_F_BN_BLINDING_UPDATE = 0x67;
static const Shortint OPENSSL_BN_F_BN_BN2DEC = 0x68;
static const Shortint OPENSSL_BN_F_BN_BN2HEX = 0x69;
static const Shortint OPENSSL_BN_F_BN_CTX_NEW = 0x6a;
static const Shortint OPENSSL_BN_F_BN_DIV = 0x6b;
static const Shortint OPENSSL_BN_F_BN_EXPAND2 = 0x6c;
static const Shortint OPENSSL_BN_F_BN_MOD_EXP_MONT = 0x6d;
static const Shortint OPENSSL_BN_F_BN_MOD_INVERSE = 0x6e;
static const Shortint OPENSSL_BN_F_BN_MOD_MUL_RECIPROCAL = 0x6f;
static const Shortint OPENSSL_BN_F_BN_MPI2BN = 0x70;
static const Shortint OPENSSL_BN_F_BN_NEW = 0x71;
static const Shortint OPENSSL_BN_F_BN_RAND = 0x72;
static const Shortint OPENSSL_BN_F_BN_USUB = 0x73;
static const unsigned OPENSSL_BN_MASK2 = 0xffffffff;
static const unsigned OPENSSL_BN_MASK2h = 0xffff0000;
static const unsigned OPENSSL_BN_MASK2h1 = 0xffff8000;
static const Word OPENSSL_BN_MASK2l = 0xffff;
static const Shortint OPENSSL_BN_R_ARG2_LT_ARG3 = 0x64;
static const Shortint OPENSSL_BN_R_BAD_RECIPROCAL = 0x65;
static const Shortint OPENSSL_BN_R_CALLED_WITH_EVEN_MODULUS = 0x66;
static const Shortint OPENSSL_BN_R_DIV_BY_ZERO = 0x67;
static const Shortint OPENSSL_BN_R_ENCODING_ERROR = 0x68;
static const Shortint OPENSSL_BN_R_EXPAND_ON_STATIC_BIGNUM_DATA = 0x69;
static const Shortint OPENSSL_BN_R_INVALID_LENGTH = 0x6a;
static const Shortint OPENSSL_BN_R_NOT_INITIALIZED = 0x6b;
static const Shortint OPENSSL_BN_R_NO_INVERSE = 0x6c;
static const unsigned OPENSSL_BN_TBIT = 0x80000000;
static const Word OPENSSL_BUFSIZ = 0x400;
static const Shortint OPENSSL_BUF_F_BUF_MEM_GROW = 0x64;
static const Shortint OPENSSL_BUF_F_BUF_MEM_NEW = 0x65;
static const Shortint OPENSSL_BUF_F_BUF_STRDUP = 0x66;
static const Word OPENSSL_B_ASN1_BIT_STRING = 0x400;
static const Word OPENSSL_B_ASN1_BMPSTRING = 0x800;
static const System::Byte OPENSSL_B_ASN1_GENERALSTRING = 0x80;
static const Shortint OPENSSL_B_ASN1_GRAPHICSTRING = 0x20;
static const Shortint OPENSSL_B_ASN1_IA5STRING = 0x10;
static const Shortint OPENSSL_B_ASN1_ISO64STRING = 0x40;
static const Shortint OPENSSL_B_ASN1_NUMERICSTRING = 0x1;
static const Word OPENSSL_B_ASN1_OCTET_STRING = 0x200;
static const Shortint OPENSSL_B_ASN1_PRINTABLESTRING = 0x2;
static const Shortint OPENSSL_B_ASN1_T61STRING = 0x4;
static const Shortint OPENSSL_B_ASN1_TELETEXSTRING = 0x8;
static const Word OPENSSL_B_ASN1_UNIVERSALSTRING = 0x100;
static const Word OPENSSL_B_ASN1_UNKNOWN = 0x1000;
static const Word OPENSSL_B_ASN1_UTF8STRING = 0x2000;
static const Shortint OPENSSL_B_ASN1_VIDEOTEXSTRING = 0x8;
static const Shortint OPENSSL_B_ASN1_VISIBLESTRING = 0x40;
static const Shortint OPENSSL_CAST_BLOCK = 0x8;
static const Shortint OPENSSL_CAST_DECRYPT = 0x0;
static const Shortint OPENSSL_CAST_ENCRYPT = 0x1;
static const Shortint OPENSSL_CAST_KEY_LENGTH = 0x10;
static const Word OPENSSL__CLOCKS_PER_SEC_ = 0x3e8;
static const Word OPENSSL_CLOCKS_PER_SEC = 0x3e8;
static const Word OPENSSL_CLK_TCK = 0x3e8;
static const Shortint OPENSSL_CRYPTO_EX_INDEX_BIO = 0x0;
static const Shortint OPENSSL_CRYPTO_EX_INDEX_SSL = 0x1;
static const Shortint OPENSSL_CRYPTO_EX_INDEX_SSL_CTX = 0x2;
static const Shortint OPENSSL_CRYPTO_EX_INDEX_SSL_SESSION = 0x3;
static const Shortint OPENSSL_CRYPTO_EX_INDEX_X509_STORE = 0x4;
static const Shortint OPENSSL_CRYPTO_EX_INDEX_X509_STORE_CTX = 0x5;
static const Shortint OPENSSL_CRYPTO_F_CRYPTO_GET_EX_NEW_INDEX = 0x64;
static const Shortint OPENSSL_CRYPTO_F_CRYPTO_GET_NEW_LOCKID = 0x65;
static const Shortint OPENSSL_CRYPTO_F_CRYPTO_SET_EX_DATA = 0x66;
static const Shortint OPENSSL_CRYPTO_LOCK = 0x1;
static const Shortint OPENSSL_CRYPTO_LOCK_BIO = 0x13;
static const Shortint OPENSSL_CRYPTO_LOCK_DSA = 0x8;
static const Shortint OPENSSL_CRYPTO_LOCK_ERR = 0x1;
static const Shortint OPENSSL_CRYPTO_LOCK_ERR_HASH = 0x2;
static const Shortint OPENSSL_CRYPTO_LOCK_EVP_PKEY = 0xa;
static const Shortint OPENSSL_CRYPTO_LOCK_GETHOSTBYNAME = 0x14;
static const Shortint OPENSSL_CRYPTO_LOCK_GETSERVBYNAME = 0x15;
static const Shortint OPENSSL_CRYPTO_LOCK_MALLOC = 0x12;
static const Shortint OPENSSL_CRYPTO_LOCK_RAND = 0x11;
static const Shortint OPENSSL_CRYPTO_LOCK_READDIR = 0x16;
static const Shortint OPENSSL_CRYPTO_LOCK_RSA = 0x9;
static const Shortint OPENSSL_CRYPTO_LOCK_RSA_BLINDING = 0x17;
static const Shortint OPENSSL_CRYPTO_LOCK_SSL = 0x10;
static const Shortint OPENSSL_CRYPTO_LOCK_SSL_CERT = 0xd;
static const Shortint OPENSSL_CRYPTO_LOCK_SSL_CTX = 0xc;
static const Shortint OPENSSL_CRYPTO_LOCK_SSL_SESSION = 0xe;
static const Shortint OPENSSL_CRYPTO_LOCK_SSL_SESS_CERT = 0xf;
static const Shortint OPENSSL_CRYPTO_LOCK_X509 = 0x3;
static const Shortint OPENSSL_CRYPTO_LOCK_X509_CRL = 0x6;
static const Shortint OPENSSL_CRYPTO_LOCK_X509_INFO = 0x4;
static const Shortint OPENSSL_CRYPTO_LOCK_X509_PKEY = 0x5;
static const Shortint OPENSSL_CRYPTO_LOCK_X509_REQ = 0x7;
static const Shortint OPENSSL_CRYPTO_LOCK_X509_STORE = 0xb;
static const Shortint OPENSSL_CRYPTO_MEM_CHECK_DISABLE = 0x3;
static const Shortint OPENSSL_CRYPTO_MEM_CHECK_ENABLE = 0x2;
static const Shortint OPENSSL_CRYPTO_MEM_CHECK_OFF = 0x0;
static const Shortint OPENSSL_CRYPTO_MEM_CHECK_ON = 0x1;
static const Shortint OPENSSL_CRYPTO_NUM_LOCKS = 0x18;
static const Shortint OPENSSL_CRYPTO_READ = 0x4;
static const Shortint OPENSSL_CRYPTO_UNLOCK = 0x2;
static const Shortint OPENSSL_CRYPTO_WRITE = 0x8;
static const Shortint OPENSSL_DES_CBC_MODE = 0x0;
static const Shortint OPENSSL_DES_DECRYPT = 0x0;
static const Shortint OPENSSL_DES_ENCRYPT = 0x1;
static const Shortint OPENSSL_DES_PCBC_MODE = 0x1;
static const Shortint OPENSSL_DH_CHECK_P_NOT_PRIME = 0x1;
static const Shortint OPENSSL_DH_CHECK_P_NOT_STRONG_PRIME = 0x2;
static const Shortint OPENSSL_DH_FLAG_CACHE_MONT_P = 0x1;
static const Shortint OPENSSL_DH_F_DHPARAMS_PRINT = 0x64;
static const Shortint OPENSSL_DH_F_DHPARAMS_PRINT_FP = 0x65;
static const Shortint OPENSSL_DH_F_DH_COMPUTE_KEY = 0x66;
static const Shortint OPENSSL_DH_F_DH_GENERATE_KEY = 0x67;
static const Shortint OPENSSL_DH_F_DH_GENERATE_PARAMETERS = 0x68;
static const Shortint OPENSSL_DH_F_DH_NEW = 0x69;
static const Shortint OPENSSL_DH_GENERATOR_2 = 0x2;
static const Shortint OPENSSL_DH_GENERATOR_5 = 0x5;
static const Shortint OPENSSL_DH_NOT_SUITABLE_GENERATOR = 0x8;
static const Shortint OPENSSL_DH_R_NO_PRIVATE_VALUE = 0x64;
static const Shortint OPENSSL_DH_UNABLE_TO_CHECK_GENERATOR = 0x4;
static const Shortint OPENSSL_DSA_FLAG_CACHE_MONT_P = 0x1;
static const Shortint OPENSSL_DSA_F_D2I_DSA_SIG = 0x6e;
static const Shortint OPENSSL_DSA_F_DSAPARAMS_PRINT = 0x64;
static const Shortint OPENSSL_DSA_F_DSAPARAMS_PRINT_FP = 0x65;
static const Shortint OPENSSL_DSA_F_DSA_DO_SIGN = 0x70;
static const Shortint OPENSSL_DSA_F_DSA_DO_VERIFY = 0x71;
static const Shortint OPENSSL_DSA_F_DSA_IS_PRIME = 0x66;
static const Shortint OPENSSL_DSA_F_DSA_NEW = 0x67;
static const Shortint OPENSSL_DSA_F_DSA_PRINT = 0x68;
static const Shortint OPENSSL_DSA_F_DSA_PRINT_FP = 0x69;
static const Shortint OPENSSL_DSA_F_DSA_SIGN = 0x6a;
static const Shortint OPENSSL_DSA_F_DSA_SIGN_SETUP = 0x6b;
static const Shortint OPENSSL_DSA_F_DSA_SIG_NEW = 0x6d;
static const Shortint OPENSSL_DSA_F_DSA_VERIFY = 0x6c;
static const Shortint OPENSSL_DSA_F_I2D_DSA_SIG = 0x6f;
static const Shortint OPENSSL_DSA_R_DATA_TOO_LARGE_FOR_KEY_SIZE = 0x64;
static const Shortint OPENSSL_EVP_BLOWFISH_KEY_SIZE = 0x10;
static const Shortint OPENSSL_EVP_CAST5_KEY_SIZE = 0x10;
static const Shortint OPENSSL_EVP_F_D2I_PKEY = 0x64;
static const Shortint OPENSSL_EVP_F_EVP_DECRYPTFINAL = 0x65;
static const Shortint OPENSSL_EVP_F_EVP_MD_CTX_COPY = 0x6e;
static const Shortint OPENSSL_EVP_F_EVP_OPENINIT = 0x66;
static const Shortint OPENSSL_EVP_F_EVP_PBE_ALG_ADD = 0x73;
static const Shortint OPENSSL_EVP_F_EVP_PBE_CIPHERINIT = 0x74;
static const Shortint OPENSSL_EVP_F_EVP_PKCS82PKEY = 0x6f;
static const Shortint OPENSSL_EVP_F_EVP_PKCS8_SET_BROKEN = 0x70;
static const Shortint OPENSSL_EVP_F_EVP_PKEY2PKCS8 = 0x71;
static const Shortint OPENSSL_EVP_F_EVP_PKEY_COPY_PARAMETERS = 0x67;
static const Shortint OPENSSL_EVP_F_EVP_PKEY_DECRYPT = 0x68;
static const Shortint OPENSSL_EVP_F_EVP_PKEY_ENCRYPT = 0x69;
static const Shortint OPENSSL_EVP_F_EVP_PKEY_NEW = 0x6a;
static const Shortint OPENSSL_EVP_F_EVP_SIGNFINAL = 0x6b;
static const Shortint OPENSSL_EVP_F_EVP_VERIFYFINAL = 0x6c;
static const Shortint OPENSSL_EVP_F_PKCS5_PBE_KEYIVGEN = 0x75;
static const Shortint OPENSSL_EVP_F_PKCS5_V2_PBE_KEYIVGEN = 0x76;
static const Shortint OPENSSL_EVP_F_RC2_MAGIC_TO_METH = 0x6d;
static const Shortint OPENSSL_EVP_MAX_IV_LENGTH = 0x8;
static const Shortint OPENSSL_EVP_MAX_KEY_LENGTH = 0x18;
static const Shortint OPENSSL_EVP_MAX_MD_SIZE = 0x24;
static const Shortint OPENSSL_NID_dhKeyAgreement = 0x1c;
static const Shortint OPENSSL_EVP_PKEY_DH = 0x1c;
static const Shortint OPENSSL_NID_dsa = 0x74;
static const Shortint OPENSSL_EVP_PKEY_DSA = 0x74;
static const Shortint OPENSSL_NID_dsa_2 = 0x43;
static const Shortint OPENSSL_EVP_PKEY_DSA1 = 0x43;
static const Shortint OPENSSL_NID_dsaWithSHA = 0x42;
static const Shortint OPENSSL_EVP_PKEY_DSA2 = 0x42;
static const Shortint OPENSSL_NID_dsaWithSHA1 = 0x71;
static const Shortint OPENSSL_EVP_PKEY_DSA3 = 0x71;
static const Shortint OPENSSL_NID_dsaWithSHA1_2 = 0x46;
static const Shortint OPENSSL_EVP_PKEY_DSA4 = 0x46;
static const Shortint OPENSSL_EVP_PKEY_MO_DECRYPT = 0x8;
static const Shortint OPENSSL_EVP_PKEY_MO_ENCRYPT = 0x4;
static const Shortint OPENSSL_EVP_PKEY_MO_SIGN = 0x1;
static const Shortint OPENSSL_EVP_PKEY_MO_VERIFY = 0x2;
static const Shortint OPENSSL_NID_undef = 0x0;
static const Shortint OPENSSL_EVP_PKEY_NONE = 0x0;
static const Shortint OPENSSL_NID_rsaEncryption = 0x6;
static const Shortint OPENSSL_EVP_PKEY_RSA = 0x6;
static const Shortint OPENSSL_NID_rsa = 0x13;
static const Shortint OPENSSL_EVP_PKEY_RSA2 = 0x13;
static const Word OPENSSL_EVP_PKS_DSA = 0x200;
static const Word OPENSSL_EVP_PKS_RSA = 0x100;
static const Shortint OPENSSL_EVP_PKT_ENC = 0x20;
static const Shortint OPENSSL_EVP_PKT_EXCH = 0x40;
static const Word OPENSSL_EVP_PKT_EXP = 0x1000;
static const Shortint OPENSSL_EVP_PKT_SIGN = 0x10;
static const Shortint OPENSSL_EVP_PK_DH = 0x4;
static const Shortint OPENSSL_EVP_PK_DSA = 0x2;
static const Shortint OPENSSL_EVP_PK_RSA = 0x1;
static const Shortint OPENSSL_EVP_RC2_KEY_SIZE = 0x10;
static const Shortint OPENSSL_EVP_RC4_KEY_SIZE = 0x10;
static const Shortint OPENSSL_EVP_RC5_32_12_16_KEY_SIZE = 0x10;
static const Shortint OPENSSL_EVP_R_BAD_DECRYPT = 0x64;
static const Shortint OPENSSL_EVP_R_BN_DECODE_ERROR = 0x70;
static const Shortint OPENSSL_EVP_R_BN_PUBKEY_ERROR = 0x71;
static const Shortint OPENSSL_EVP_R_CIPHER_PARAMETER_ERROR = 0x7a;
static const Shortint OPENSSL_EVP_R_DECODE_ERROR = 0x72;
static const Shortint OPENSSL_EVP_R_DIFFERENT_KEY_TYPES = 0x65;
static const Shortint OPENSSL_EVP_R_ENCODE_ERROR = 0x73;
static const Shortint OPENSSL_EVP_R_EVP_PBE_CIPHERINIT_ERROR = 0x77;
static const Shortint OPENSSL_EVP_R_INPUT_NOT_INITIALIZED = 0x6f;
static const Shortint OPENSSL_EVP_R_IV_TOO_LARGE = 0x66;
static const Shortint OPENSSL_EVP_R_KEYGEN_FAILURE = 0x78;
static const Shortint OPENSSL_EVP_R_MISSING_PARMATERS = 0x67;
static const Shortint OPENSSL_EVP_R_NO_DSA_PARAMETERS = 0x74;
static const Shortint OPENSSL_EVP_R_NO_SIGN_FUNCTION_CONFIGURED = 0x68;
static const Shortint OPENSSL_EVP_R_NO_VERIFY_FUNCTION_CONFIGURED = 0x69;
static const Shortint OPENSSL_EVP_R_PKCS8_UNKNOWN_BROKEN_TYPE = 0x75;
static const Shortint OPENSSL_EVP_R_PUBLIC_KEY_NOT_RSA = 0x6a;
static const Shortint OPENSSL_EVP_R_UNKNOWN_PBE_ALGORITHM = 0x79;
static const Shortint OPENSSL_EVP_R_UNSUPPORTED_CIPHER = 0x6b;
static const Shortint OPENSSL_EVP_R_UNSUPPORTED_KEYLENGTH = 0x7b;
static const Shortint OPENSSL_EVP_R_UNSUPPORTED_KEY_DERIVATION_FUNCTION = 0x7c;
static const Shortint OPENSSL_EVP_R_UNSUPPORTED_KEY_SIZE = 0x6c;
static const Shortint OPENSSL_EVP_R_UNSUPPORTED_PRF = 0x7d;
static const Shortint OPENSSL_EVP_R_UNSUPPORTED_PRIVATE_KEY_ALGORITHM = 0x76;
static const Shortint OPENSSL_EVP_R_UNSUPPORTED_SALT_TYPE = 0x7e;
static const Shortint OPENSSL_EVP_R_WRONG_FINAL_BLOCK_LENGTH = 0x6d;
static const Shortint OPENSSL_EVP_R_WRONG_PUBLIC_KEY_TYPE = 0x6e;
static const Shortint OPENSSL_MSS_EXIT_FAILURE = 0x1;
static const Shortint OPENSSL_MSS_EXIT_SUCCESS = 0x0;
static const Word OPENSSL_FILENAME_MAX = 0x400;
static const Shortint OPENSSL_FOPEN_MAX = 0x14;
static const Shortint OPENSSL_IDEA_BLOCK = 0x8;
static const Shortint OPENSSL_IDEA_DECRYPT = 0x0;
static const Shortint OPENSSL_IDEA_ENCRYPT = 0x1;
static const Shortint OPENSSL_IDEA_KEY_LENGTH = 0x10;
static const Shortint OPENSSL_IS_SEQUENCE = 0x0;
static const Shortint OPENSSL_IS_SET = 0x1;
static const Shortint OPENSSL_KRBDES_DECRYPT = 0x0;
static const Shortint OPENSSL_KRBDES_ENCRYPT = 0x1;
static const Word OPENSSL_LH_LOAD_MULT = 0x100;
#define OPENSSL_LN_SMIMECapabilities "S/MIME Capabilities"
#define OPENSSL_LN_X500 "X500"
#define OPENSSL_LN_X509 "X509"
#define OPENSSL_LN_algorithm "algorithm"
#define OPENSSL_LN_authority_key_identifier "X509v3 Authority Key Identifier"
#define OPENSSL_LN_basic_constraints "X509v3 Basic Constraints"
#define OPENSSL_LN_bf_cbc "bf-cbc"
#define OPENSSL_LN_bf_cfb64 "bf-cfb"
#define OPENSSL_LN_bf_ecb "bf-ecb"
#define OPENSSL_LN_bf_ofb64 "bf-ofb"
#define OPENSSL_LN_cast5_cbc "cast5-cbc"
#define OPENSSL_LN_cast5_cfb64 "cast5-cfb"
#define OPENSSL_LN_cast5_ecb "cast5-ecb"
#define OPENSSL_LN_cast5_ofb64 "cast5-ofb"
#define OPENSSL_LN_certBag "certBag"
#define OPENSSL_LN_certificate_policies "X509v3 Certificate Policies"
#define OPENSSL_LN_client_auth "TLS Web Client Authentication"
#define OPENSSL_LN_code_sign "Code Signing"
#define OPENSSL_LN_commonName "commonName"
#define OPENSSL_LN_countryName "countryName"
#define OPENSSL_LN_crlBag "crlBag"
#define OPENSSL_LN_crl_distribution_points "X509v3 CRL Distribution Points"
#define OPENSSL_LN_crl_number "X509v3 CRL Number"
#define OPENSSL_LN_crl_reason "CRL Reason Code"
#define OPENSSL_LN_delta_crl "X509v3 Delta CRL Indicator"
#define OPENSSL_LN_des_cbc "des-cbc"
#define OPENSSL_LN_des_cfb64 "des-cfb"
#define OPENSSL_LN_des_ecb "des-ecb"
#define OPENSSL_LN_des_ede "des-ede"
#define OPENSSL_LN_des_ede3 "des-ede3"
#define OPENSSL_LN_des_ede3_cbc "des-ede3-cbc"
#define OPENSSL_LN_des_ede3_cfb64 "des-ede3-cfb"
#define OPENSSL_LN_des_ede3_ofb64 "des-ede3-ofb"
#define OPENSSL_LN_des_ede_cbc "des-ede-cbc"
#define OPENSSL_LN_des_ede_cfb64 "des-ede-cfb"
#define OPENSSL_LN_des_ede_ofb64 "des-ede-ofb"
#define OPENSSL_LN_des_ofb64 "des-ofb"
#define OPENSSL_LN_description "description"
#define OPENSSL_LN_desx_cbc "desx-cbc"
#define OPENSSL_LN_dhKeyAgreement "dhKeyAgreement"
#define OPENSSL_LN_dsa "dsaEncryption"
#define OPENSSL_LN_dsaWithSHA "dsaWithSHA"
#define OPENSSL_LN_dsaWithSHA1 "dsaWithSHA1"
#define OPENSSL_LN_dsaWithSHA1_2 "dsaWithSHA1-old"
#define OPENSSL_LN_dsa_2 "dsaEncryption-old"
#define OPENSSL_LN_email_protect "E-mail Protection"
#define OPENSSL_LN_ext_key_usage "X509v3 Extended Key Usage"
#define OPENSSL_LN_friendlyName "friendlyName"
#define OPENSSL_LN_givenName "givenName"
#define OPENSSL_LN_hmacWithSHA1 "hmacWithSHA1"
#define OPENSSL_LN_id_pbkdf2 "PBKDF2"
#define OPENSSL_LN_id_qt_cps "Policy Qualifier CPS"
#define OPENSSL_LN_id_qt_unotice "Policy Qualifier User Notice"
#define OPENSSL_LN_idea_cbc "idea-cbc"
#define OPENSSL_LN_idea_cfb64 "idea-cfb"
#define OPENSSL_LN_idea_ecb "idea-ecb"
#define OPENSSL_LN_idea_ofb64 "idea-ofb"
#define OPENSSL_LN_initials "initials"
#define OPENSSL_LN_invalidity_date "Invalidity Date"
#define OPENSSL_LN_issuer_alt_name "X509v3 Issuer Alternative Name"
#define OPENSSL_LN_keyBag "keyBag"
#define OPENSSL_LN_key_usage "X509v3 Key Usage"
#define OPENSSL_LN_localKeyID "localKeyID"
#define OPENSSL_LN_localityName "localityName"
#define OPENSSL_LN_md2 "md2"
#define OPENSSL_LN_md2WithRSAEncryption "md2WithRSAEncryption"
#define OPENSSL_LN_md5 "md5"
#define OPENSSL_LN_md5WithRSA "md5WithRSA"
#define OPENSSL_LN_md5WithRSAEncryption "md5WithRSAEncryption"
#define OPENSSL_LN_md5_sha1 "md5-sha1"
#define OPENSSL_LN_mdc2 "mdc2"
#define OPENSSL_LN_mdc2WithRSA "mdc2withRSA"
#define OPENSSL_LN_ms_code_com "Microsoft Commercial Code Signing"
#define OPENSSL_LN_ms_code_ind "Microsoft Individual Code Signing"
#define OPENSSL_LN_ms_ctl_sign "Microsoft Trust List Signing"
#define OPENSSL_LN_ms_efs "Microsoft Encrypted File System"
#define OPENSSL_LN_ms_sgc "Microsoft Server Gated Crypto"
#define OPENSSL_LN_netscape "Netscape Communications Corp."
#define OPENSSL_LN_netscape_base_url "Netscape Base Url"
#define OPENSSL_LN_netscape_ca_policy_url "Netscape CA Policy Url"
#define OPENSSL_LN_netscape_ca_revocation_url "Netscape CA Revocation Url"
#define OPENSSL_LN_netscape_cert_extension "Netscape Certificate Extension"
#define OPENSSL_LN_netscape_cert_sequence "Netscape Certificate Sequence"
#define OPENSSL_LN_netscape_cert_type "Netscape Cert Type"
#define OPENSSL_LN_netscape_comment "Netscape Comment"
#define OPENSSL_LN_netscape_data_type "Netscape Data Type"
#define OPENSSL_LN_netscape_renewal_url "Netscape Renewal Url"
#define OPENSSL_LN_netscape_revocation_url "Netscape Revocation Url"
#define OPENSSL_LN_netscape_ssl_server_name "Netscape SSL Server Name"
#define OPENSSL_LN_ns_sgc "Netscape Server Gated Crypto"
#define OPENSSL_LN_organizationName "organizationName"
#define OPENSSL_LN_organizationalUnitName "organizationalUnitName"
#define OPENSSL_LN_pbeWithMD2AndDES_CBC "pbeWithMD2AndDES-CBC"
#define OPENSSL_LN_pbeWithMD2AndRC2_CBC "pbeWithMD2AndRC2-CBC"
#define OPENSSL_LN_pbeWithMD5AndCast5_CBC "pbeWithMD5AndCast5CBC"
#define OPENSSL_LN_pbeWithMD5AndDES_CBC "pbeWithMD5AndDES-CBC"
#define OPENSSL_LN_pbeWithMD5AndRC2_CBC "pbeWithMD5AndRC2-CBC"
#define OPENSSL_LN_pbeWithSHA1AndDES_CBC "pbeWithSHA1AndDES-CBC"
#define OPENSSL_LN_pbeWithSHA1AndRC2_CBC "pbeWithSHA1AndRC2-CBC"
#define OPENSSL_LN_pbe_WithSHA1And128BitRC2_CBC "pbeWithSHA1And128BitRC2-CBC"
#define OPENSSL_LN_pbe_WithSHA1And128BitRC4 "pbeWithSHA1And128BitRC4"
#define OPENSSL_LN_pbe_WithSHA1And2_Key_TripleDES_CBC "pbeWithSHA1And2-KeyTripleDES-CBC"
#define OPENSSL_LN_pbe_WithSHA1And3_Key_TripleDES_CBC "pbeWithSHA1And3-KeyTripleDES-CBC"
#define OPENSSL_LN_pbe_WithSHA1And40BitRC2_CBC "pbeWithSHA1And40BitRC2-CBC"
#define OPENSSL_LN_pbe_WithSHA1And40BitRC4 "pbeWithSHA1And40BitRC4"
#define OPENSSL_LN_pbes2 "PBES2"
#define OPENSSL_LN_pbmac1 "PBMAC1"
#define OPENSSL_LN_pkcs "pkcs"
#define OPENSSL_LN_pkcs3 "pkcs3"
#define OPENSSL_LN_pkcs7 "pkcs7"
#define OPENSSL_LN_pkcs7_data "pkcs7-data"
#define OPENSSL_LN_pkcs7_digest "pkcs7-digestData"
#define OPENSSL_LN_pkcs7_encrypted "pkcs7-encryptedData"
#define OPENSSL_LN_pkcs7_enveloped "pkcs7-envelopedData"
#define OPENSSL_LN_pkcs7_signed "pkcs7-signedData"
#define OPENSSL_LN_pkcs7_signedAndEnveloped "pkcs7-signedAndEnvelopedData"
#define OPENSSL_LN_pkcs8ShroudedKeyBag "pkcs8ShroudedKeyBag"
#define OPENSSL_LN_pkcs9 "pkcs9"
#define OPENSSL_LN_pkcs9_challengePassword "challengePassword"
#define OPENSSL_LN_pkcs9_contentType "contentType"
#define OPENSSL_LN_pkcs9_countersignature "countersignature"
#define OPENSSL_LN_pkcs9_emailAddress "emailAddress"
#define OPENSSL_LN_pkcs9_extCertAttributes "extendedCertificateAttributes"
#define OPENSSL_LN_pkcs9_messageDigest "messageDigest"
#define OPENSSL_LN_pkcs9_signingTime "signingTime"
#define OPENSSL_LN_pkcs9_unstructuredAddress "unstructuredAddress"
#define OPENSSL_LN_pkcs9_unstructuredName "unstructuredName"
#define OPENSSL_LN_private_key_usage_period "X509v3 Private Key Usage Period"
#define OPENSSL_LN_rc2_40_cbc "rc2-40-cbc"
#define OPENSSL_LN_rc2_64_cbc "rc2-64-cbc"
#define OPENSSL_LN_rc2_cbc "rc2-cbc"
#define OPENSSL_LN_rc2_cfb64 "rc2-cfb"
#define OPENSSL_LN_rc2_ecb "rc2-ecb"
#define OPENSSL_LN_rc2_ofb64 "rc2-ofb"
#define OPENSSL_LN_rc4 "rc4"
#define OPENSSL_LN_rc4_40 "rc4-40"
#define OPENSSL_LN_rc5_cbc "rc5-cbc"
#define OPENSSL_LN_rc5_cfb64 "rc5-cfb"
#define OPENSSL_LN_rc5_ecb "rc5-ecb"
#define OPENSSL_LN_rc5_ofb64 "rc5-ofb"
#define OPENSSL_LN_ripemd160 "ripemd160"
#define OPENSSL_LN_ripemd160WithRSA "ripemd160WithRSA"
#define OPENSSL_LN_rle_compression "run length compression"
#define OPENSSL_LN_rsa "rsa"
#define OPENSSL_LN_rsaEncryption "rsaEncryption"
#define OPENSSL_LN_rsadsi "rsadsi"
#define OPENSSL_LN_safeContentsBag "safeContentsBag"
#define OPENSSL_LN_sdsiCertificate "sdsiCertificate"
#define OPENSSL_LN_secretBag "secretBag"
#define OPENSSL_LN_serialNumber "serialNumber"
#define OPENSSL_LN_server_auth "TLS Web Server Authentication"
#define OPENSSL_LN_sha "sha"
#define OPENSSL_LN_sha1 "sha1"
#define OPENSSL_LN_sha1WithRSA "sha1WithRSA"
#define OPENSSL_LN_sha1WithRSAEncryption "sha1WithRSAEncryption"
#define OPENSSL_LN_shaWithRSAEncryption "shaWithRSAEncryption"
#define OPENSSL_LN_stateOrProvinceName "stateOrProvinceName"
#define OPENSSL_LN_subject_alt_name "X509v3 Subject Alternative Name"
#define OPENSSL_LN_subject_key_identifier "X509v3 Subject Key Identifier"
#define OPENSSL_LN_surname "surname"
#define OPENSSL_LN_sxnet "Strong Extranet ID"
#define OPENSSL_LN_time_stamp "Time Stamping"
#define OPENSSL_LN_title "title"
#define OPENSSL_LN_undef "undefined"
#define OPENSSL_LN_uniqueIdentifier "uniqueIdentifier"
#define OPENSSL_LN_x509Certificate "x509Certificate"
#define OPENSSL_LN_x509Crl "x509Crl"
#define OPENSSL_LN_zlib_compression "zlib compression"
static const Shortint OPENSSL_L_ctermid = 0x10;
static const Shortint OPENSSL_L_cuserid = 0x9;
static const Word OPENSSL_L_tmpnam = 0x400;
static const Shortint OPENSSL_MD2_BLOCK = 0x10;
static const Shortint OPENSSL_MD2_DIGEST_LENGTH = 0x10;
static const Shortint OPENSSL_MD5_CBLOCK = 0x40;
static const Shortint OPENSSL_MD5_DIGEST_LENGTH = 0x10;
static const Shortint OPENSSL_MDC2_BLOCK = 0x8;
static const Shortint OPENSSL_MDC2_DIGEST_LENGTH = 0x10;
static const System::Byte OPENSSL_NID_SMIMECapabilities = 0xa7;
static const Shortint OPENSSL_NID_X500 = 0xb;
static const Shortint OPENSSL_NID_X509 = 0xc;
static const Shortint OPENSSL_NID_algorithm = 0x26;
static const Shortint OPENSSL_NID_authority_key_identifier = 0x5a;
static const Shortint OPENSSL_NID_basic_constraints = 0x57;
static const Shortint OPENSSL_NID_bf_cbc = 0x5b;
static const Shortint OPENSSL_NID_bf_cfb64 = 0x5d;
static const Shortint OPENSSL_NID_bf_ecb = 0x5c;
static const Shortint OPENSSL_NID_bf_ofb64 = 0x5e;
static const Shortint OPENSSL_NID_cast5_cbc = 0x6c;
static const Shortint OPENSSL_NID_cast5_cfb64 = 0x6e;
static const Shortint OPENSSL_NID_cast5_ecb = 0x6d;
static const Shortint OPENSSL_NID_cast5_ofb64 = 0x6f;
static const System::Byte OPENSSL_NID_certBag = 0x98;
static const Shortint OPENSSL_NID_certificate_policies = 0x59;
static const System::Byte OPENSSL_NID_client_auth = 0x82;
static const System::Byte OPENSSL_NID_code_sign = 0x83;
static const Shortint OPENSSL_NID_commonName = 0xd;
static const Shortint OPENSSL_NID_countryName = 0xe;
static const System::Byte OPENSSL_NID_crlBag = 0x99;
static const Shortint OPENSSL_NID_crl_distribution_points = 0x67;
static const Shortint OPENSSL_NID_crl_number = 0x58;
static const System::Byte OPENSSL_NID_crl_reason = 0x8d;
static const System::Byte OPENSSL_NID_delta_crl = 0x8c;
static const Shortint OPENSSL_NID_des_cbc = 0x1f;
static const Shortint OPENSSL_NID_des_cfb64 = 0x1e;
static const Shortint OPENSSL_NID_des_ecb = 0x1d;
static const Shortint OPENSSL_NID_des_ede = 0x20;
static const Shortint OPENSSL_NID_des_ede3 = 0x21;
static const Shortint OPENSSL_NID_des_ede3_cbc = 0x2c;
static const Shortint OPENSSL_NID_des_ede3_cfb64 = 0x3d;
static const Shortint OPENSSL_NID_des_ede3_ofb64 = 0x3f;
static const Shortint OPENSSL_NID_des_ede_cbc = 0x2b;
static const Shortint OPENSSL_NID_des_ede_cfb64 = 0x3c;
static const Shortint OPENSSL_NID_des_ede_ofb64 = 0x3e;
static const Shortint OPENSSL_NID_des_ofb64 = 0x2d;
static const Shortint OPENSSL_NID_description = 0x6b;
static const Shortint OPENSSL_NID_desx_cbc = 0x50;
static const System::Byte OPENSSL_NID_email_protect = 0x84;
static const Shortint OPENSSL_NID_ext_key_usage = 0x7e;
static const System::Byte OPENSSL_NID_friendlyName = 0x9c;
static const Shortint OPENSSL_NID_givenName = 0x63;
static const System::Byte OPENSSL_NID_hmacWithSHA1 = 0xa3;
static const System::Byte OPENSSL_NID_id_kp = 0x80;
static const Shortint OPENSSL_NID_id_pbkdf2 = 0x45;
static const Shortint OPENSSL_NID_id_pkix = 0x7f;
static const System::Byte OPENSSL_NID_id_qt_cps = 0xa4;
static const System::Byte OPENSSL_NID_id_qt_unotice = 0xa5;
static const Shortint OPENSSL_NID_idea_cbc = 0x22;
static const Shortint OPENSSL_NID_idea_cfb64 = 0x23;
static const Shortint OPENSSL_NID_idea_ecb = 0x24;
static const Shortint OPENSSL_NID_idea_ofb64 = 0x2e;
static const Shortint OPENSSL_NID_initials = 0x65;
static const System::Byte OPENSSL_NID_invalidity_date = 0x8e;
static const Shortint OPENSSL_NID_issuer_alt_name = 0x56;
static const System::Byte OPENSSL_NID_keyBag = 0x96;
static const Shortint OPENSSL_NID_key_usage = 0x53;
static const Shortint OPENSSL_NID_ld_ce = 0x51;
static const System::Byte OPENSSL_NID_localKeyID = 0x9d;
static const Shortint OPENSSL_NID_localityName = 0xf;
static const Shortint OPENSSL_NID_md2 = 0x3;
static const Shortint OPENSSL_NID_md2WithRSAEncryption = 0x7;
static const Shortint OPENSSL_NID_md5 = 0x4;
static const Shortint OPENSSL_NID_md5WithRSA = 0x68;
static const Shortint OPENSSL_NID_md5WithRSAEncryption = 0x8;
static const Shortint OPENSSL_NID_md5_sha1 = 0x72;
static const Shortint OPENSSL_NID_mdc2 = 0x5f;
static const Shortint OPENSSL_NID_mdc2WithRSA = 0x60;
static const System::Byte OPENSSL_NID_ms_code_com = 0x87;
static const System::Byte OPENSSL_NID_ms_code_ind = 0x86;
static const System::Byte OPENSSL_NID_ms_ctl_sign = 0x88;
static const System::Byte OPENSSL_NID_ms_efs = 0x8a;
static const System::Byte OPENSSL_NID_ms_sgc = 0x89;
static const Shortint OPENSSL_NID_netscape = 0x39;
static const Shortint OPENSSL_NID_netscape_base_url = 0x48;
static const Shortint OPENSSL_NID_netscape_ca_policy_url = 0x4c;
static const Shortint OPENSSL_NID_netscape_ca_revocation_url = 0x4a;
static const Shortint OPENSSL_NID_netscape_cert_extension = 0x3a;
static const Shortint OPENSSL_NID_netscape_cert_sequence = 0x4f;
static const Shortint OPENSSL_NID_netscape_cert_type = 0x47;
static const Shortint OPENSSL_NID_netscape_comment = 0x4e;
static const Shortint OPENSSL_NID_netscape_data_type = 0x3b;
static const Shortint OPENSSL_NID_netscape_renewal_url = 0x4b;
static const Shortint OPENSSL_NID_netscape_revocation_url = 0x49;
static const Shortint OPENSSL_NID_netscape_ssl_server_name = 0x4d;
static const System::Byte OPENSSL_NID_ns_sgc = 0x8b;
static const Shortint OPENSSL_NID_organizationName = 0x11;
static const Shortint OPENSSL_NID_organizationalUnitName = 0x12;
static const Shortint OPENSSL_NID_pbeWithMD2AndDES_CBC = 0x9;
static const System::Byte OPENSSL_NID_pbeWithMD2AndRC2_CBC = 0xa8;
static const Shortint OPENSSL_NID_pbeWithMD5AndCast5_CBC = 0x70;
static const Shortint OPENSSL_NID_pbeWithMD5AndDES_CBC = 0xa;
static const System::Byte OPENSSL_NID_pbeWithMD5AndRC2_CBC = 0xa9;
static const System::Byte OPENSSL_NID_pbeWithSHA1AndDES_CBC = 0xaa;
static const Shortint OPENSSL_NID_pbeWithSHA1AndRC2_CBC = 0x44;
static const System::Byte OPENSSL_NID_pbe_WithSHA1And128BitRC2_CBC = 0x94;
static const System::Byte OPENSSL_NID_pbe_WithSHA1And128BitRC4 = 0x90;
static const System::Byte OPENSSL_NID_pbe_WithSHA1And2_Key_TripleDES_CBC = 0x93;
static const System::Byte OPENSSL_NID_pbe_WithSHA1And3_Key_TripleDES_CBC = 0x92;
static const System::Byte OPENSSL_NID_pbe_WithSHA1And40BitRC2_CBC = 0x95;
static const System::Byte OPENSSL_NID_pbe_WithSHA1And40BitRC4 = 0x91;
static const System::Byte OPENSSL_NID_pbes2 = 0xa1;
static const System::Byte OPENSSL_NID_pbmac1 = 0xa2;
static const Shortint OPENSSL_NID_pkcs = 0x2;
static const Shortint OPENSSL_NID_pkcs3 = 0x1b;
static const Shortint OPENSSL_NID_pkcs7 = 0x14;
static const Shortint OPENSSL_NID_pkcs7_data = 0x15;
static const Shortint OPENSSL_NID_pkcs7_digest = 0x19;
static const Shortint OPENSSL_NID_pkcs7_encrypted = 0x1a;
static const Shortint OPENSSL_NID_pkcs7_enveloped = 0x17;
static const Shortint OPENSSL_NID_pkcs7_signed = 0x16;
static const Shortint OPENSSL_NID_pkcs7_signedAndEnveloped = 0x18;
static const System::Byte OPENSSL_NID_pkcs8ShroudedKeyBag = 0x97;
static const Shortint OPENSSL_NID_pkcs9 = 0x2f;
static const Shortint OPENSSL_NID_pkcs9_challengePassword = 0x36;
static const Shortint OPENSSL_NID_pkcs9_contentType = 0x32;
static const Shortint OPENSSL_NID_pkcs9_countersignature = 0x35;
static const Shortint OPENSSL_NID_pkcs9_emailAddress = 0x30;
static const Shortint OPENSSL_NID_pkcs9_extCertAttributes = 0x38;
static const Shortint OPENSSL_NID_pkcs9_messageDigest = 0x33;
static const Shortint OPENSSL_NID_pkcs9_signingTime = 0x34;
static const Shortint OPENSSL_NID_pkcs9_unstructuredAddress = 0x37;
static const Shortint OPENSSL_NID_pkcs9_unstructuredName = 0x31;
static const Shortint OPENSSL_NID_private_key_usage_period = 0x54;
static const Shortint OPENSSL_NID_rc2_40_cbc = 0x62;
static const System::Byte OPENSSL_NID_rc2_64_cbc = 0xa6;
static const Shortint OPENSSL_NID_rc2_cbc = 0x25;
static const Shortint OPENSSL_NID_rc2_cfb64 = 0x27;
static const Shortint OPENSSL_NID_rc2_ecb = 0x26;
static const Shortint OPENSSL_NID_rc2_ofb64 = 0x28;
static const Shortint OPENSSL_NID_rc4 = 0x5;
static const Shortint OPENSSL_NID_rc4_40 = 0x61;
static const Shortint OPENSSL_NID_rc5_cbc = 0x78;
static const Shortint OPENSSL_NID_rc5_cfb64 = 0x7a;
static const Shortint OPENSSL_NID_rc5_ecb = 0x79;
static const Shortint OPENSSL_NID_rc5_ofb64 = 0x7b;
static const Shortint OPENSSL_NID_ripemd160 = 0x75;
static const Shortint OPENSSL_NID_ripemd160WithRSA = 0x77;
static const Shortint OPENSSL_NID_rle_compression = 0x7c;
static const Shortint OPENSSL_NID_rsadsi = 0x1;
static const System::Byte OPENSSL_NID_safeContentsBag = 0x9b;
static const System::Byte OPENSSL_NID_sdsiCertificate = 0x9f;
static const System::Byte OPENSSL_NID_secretBag = 0x9a;
static const Shortint OPENSSL_NID_serialNumber = 0x69;
static const System::Byte OPENSSL_NID_server_auth = 0x81;
static const Shortint OPENSSL_NID_sha = 0x29;
static const Shortint OPENSSL_NID_sha1 = 0x40;
static const Shortint OPENSSL_NID_sha1WithRSA = 0x73;
static const Shortint OPENSSL_NID_sha1WithRSAEncryption = 0x41;
static const Shortint OPENSSL_NID_shaWithRSAEncryption = 0x2a;
static const Shortint OPENSSL_NID_stateOrProvinceName = 0x10;
static const Shortint OPENSSL_NID_subject_alt_name = 0x55;
static const Shortint OPENSSL_NID_subject_key_identifier = 0x52;
static const Shortint OPENSSL_NID_surname = 0x64;
static const System::Byte OPENSSL_NID_sxnet = 0x8f;
static const System::Byte OPENSSL_NID_time_stamp = 0x85;
static const Shortint OPENSSL_NID_title = 0x6a;
static const Shortint OPENSSL_NID_uniqueIdentifier = 0x66;
static const System::Byte OPENSSL_NID_x509Certificate = 0x9e;
static const System::Byte OPENSSL_NID_x509Crl = 0xa0;
static const Shortint OPENSSL_NID_zlib_compression = 0x7d;
static const Shortint OPENSSL_OBJ_F_OBJ_CREATE = 0x64;
static const Shortint OPENSSL_OBJ_F_OBJ_DUP = 0x65;
static const Shortint OPENSSL_OBJ_F_OBJ_NID2LN = 0x66;
static const Shortint OPENSSL_OBJ_F_OBJ_NID2OBJ = 0x67;
static const Shortint OPENSSL_OBJ_F_OBJ_NID2SN = 0x68;
static const Word OPENSSL_OBJ_NAME_ALIAS = 0x8000;
static const Shortint OPENSSL_OBJ_NAME_TYPE_CIPHER_METH = 0x2;
static const Shortint OPENSSL_OBJ_NAME_TYPE_COMP_METH = 0x4;
static const Shortint OPENSSL_OBJ_NAME_TYPE_MD_METH = 0x1;
static const Shortint OPENSSL_OBJ_NAME_TYPE_NUM = 0x5;
static const Shortint OPENSSL_OBJ_NAME_TYPE_PKEY_METH = 0x3;
static const Shortint OPENSSL_OBJ_NAME_TYPE_UNDEF = 0x0;
static const Shortint OPENSSL_OBJ_R_MALLOC_FAILURE = 0x64;
static const Shortint OPENSSL_OBJ_R_UNKNOWN_NID = 0x65;
static const int OPENSSL_OPENSSL_VERSION_NUMBER = 0x904100;
#define OPENSSL_OPENSSL_VERSION_TEXT "OpenSSL 0.9.4 09 Aug 1999"
static const Word OPENSSL_PEM_BUFSIZE = 0x400;
static const Shortint OPENSSL_PEM_DEK_DES_CBC = 0x28;
static const Shortint OPENSSL_PEM_DEK_DES_ECB = 0x3c;
static const Shortint OPENSSL_PEM_DEK_DES_EDE = 0x32;
static const Shortint OPENSSL_PEM_DEK_IDEA_CBC = 0x2d;
static const Shortint OPENSSL_PEM_DEK_RSA = 0x46;
static const Shortint OPENSSL_PEM_DEK_RSA_MD2 = 0x50;
static const Shortint OPENSSL_PEM_DEK_RSA_MD5 = 0x5a;
static const Shortint OPENSSL_PEM_ERROR = 0x1e;
static const Shortint OPENSSL_PEM_F_DEF_CALLBACK = 0x64;
static const Shortint OPENSSL_PEM_F_LOAD_IV = 0x65;
static const Shortint OPENSSL_PEM_F_PEM_ASN1_READ = 0x66;
static const Shortint OPENSSL_PEM_F_PEM_ASN1_READ_BIO = 0x67;
static const Shortint OPENSSL_PEM_F_PEM_ASN1_WRITE = 0x68;
static const Shortint OPENSSL_PEM_F_PEM_ASN1_WRITE_BIO = 0x69;
static const Shortint OPENSSL_PEM_F_PEM_DO_HEADER = 0x6a;
static const Shortint OPENSSL_PEM_F_PEM_F_PEM_WRITE_PKCS8PRIVATEKEY = 0x76;
static const Shortint OPENSSL_PEM_F_PEM_GET_EVP_CIPHER_INFO = 0x6b;
static const Shortint OPENSSL_PEM_F_PEM_READ = 0x6c;
static const Shortint OPENSSL_PEM_F_PEM_READ_BIO = 0x6d;
static const Shortint OPENSSL_PEM_F_PEM_SEALFINAL = 0x6e;
static const Shortint OPENSSL_PEM_F_PEM_SEALINIT = 0x6f;
static const Shortint OPENSSL_PEM_F_PEM_SIGNFINAL = 0x70;
static const Shortint OPENSSL_PEM_F_PEM_WRITE = 0x71;
static const Shortint OPENSSL_PEM_F_PEM_WRITE_BIO = 0x72;
static const Shortint OPENSSL_PEM_F_PEM_WRITE_BIO_PKCS8PRIVATEKEY = 0x77;
static const Shortint OPENSSL_PEM_F_PEM_X509_INFO_READ = 0x73;
static const Shortint OPENSSL_PEM_F_PEM_X509_INFO_READ_BIO = 0x74;
static const Shortint OPENSSL_PEM_F_PEM_X509_INFO_WRITE_BIO = 0x75;
static const Shortint OPENSSL_PEM_MD_MD2 = 0x3;
static const Shortint OPENSSL_PEM_MD_MD2_RSA = 0x7;
static const Shortint OPENSSL_PEM_MD_MD5 = 0x4;
static const Shortint OPENSSL_PEM_MD_MD5_RSA = 0x8;
static const Shortint OPENSSL_PEM_MD_SHA = 0x29;
static const Shortint OPENSSL_PEM_MD_SHA_RSA = 0x41;
static const Shortint OPENSSL_PEM_OBJ_CRL = 0x3;
static const Shortint OPENSSL_PEM_OBJ_DHPARAMS = 0x11;
static const Shortint OPENSSL_PEM_OBJ_DSAPARAMS = 0x12;
static const Shortint OPENSSL_PEM_OBJ_PRIV_DH = 0xd;
static const Shortint OPENSSL_PEM_OBJ_PRIV_DSA = 0xc;
static const Shortint OPENSSL_PEM_OBJ_PRIV_KEY = 0xa;
static const Shortint OPENSSL_PEM_OBJ_PRIV_RSA = 0xb;
static const Shortint OPENSSL_PEM_OBJ_PRIV_RSA_PUBLIC = 0x13;
static const Shortint OPENSSL_PEM_OBJ_PUB_DH = 0x10;
static const Shortint OPENSSL_PEM_OBJ_PUB_DSA = 0xf;
static const Shortint OPENSSL_PEM_OBJ_PUB_RSA = 0xe;
static const Shortint OPENSSL_PEM_OBJ_SSL_SESSION = 0x4;
static const Shortint OPENSSL_PEM_OBJ_UNDEF = 0x0;
static const Shortint OPENSSL_PEM_OBJ_X509 = 0x1;
static const Shortint OPENSSL_PEM_OBJ_X509_REQ = 0x2;
static const Shortint OPENSSL_PEM_R_BAD_BASE64_DECODE = 0x64;
static const Shortint OPENSSL_PEM_R_BAD_DECRYPT = 0x65;
static const Shortint OPENSSL_PEM_R_BAD_END_LINE = 0x66;
static const Shortint OPENSSL_PEM_R_BAD_IV_CHARS = 0x67;
static const Shortint OPENSSL_PEM_R_BAD_PASSWORD_READ = 0x68;
static const Shortint OPENSSL_PEM_R_ERROR_CONVERTING_PRIVATE_KEY = 0x73;
static const Shortint OPENSSL_PEM_R_NOT_DEK_INFO = 0x69;
static const Shortint OPENSSL_PEM_R_NOT_ENCRYPTED = 0x6a;
static const Shortint OPENSSL_PEM_R_NOT_PROC_TYPE = 0x6b;
static const Shortint OPENSSL_PEM_R_NO_START_LINE = 0x6c;
static const Shortint OPENSSL_PEM_R_PROBLEMS_GETTING_PASSWORD = 0x6d;
static const Shortint OPENSSL_PEM_R_PUBLIC_KEY_NO_RSA = 0x6e;
static const Shortint OPENSSL_PEM_R_READ_KEY = 0x6f;
static const Shortint OPENSSL_PEM_R_SHORT_HEADER = 0x70;
static const Shortint OPENSSL_PEM_R_UNSUPPORTED_CIPHER = 0x71;
static const Shortint OPENSSL_PEM_R_UNSUPPORTED_ENCRYPTION = 0x72;
#define OPENSSL_PEM_STRING_DHPARAMS "DH PARAMETERS"
#define OPENSSL_PEM_STRING_DSA "DSA PRIVATE KEY"
#define OPENSSL_PEM_STRING_DSAPARAMS "DSA PARAMETERS"
#define OPENSSL_PEM_STRING_EVP_PKEY "ANY PRIVATE KEY"
#define OPENSSL_PEM_STRING_PKCS7 "PKCS7"
#define OPENSSL_PEM_STRING_PKCS8 "ENCRYPTED PRIVATE KEY"
#define OPENSSL_PEM_STRING_PKCS8INF "PRIVATE KEY"
#define OPENSSL_PEM_STRING_RSA "RSA PRIVATE KEY"
#define OPENSSL_PEM_STRING_RSA_PUBLIC "RSA PUBLIC KEY"
#define OPENSSL_PEM_STRING_SSL_SESSION "SSL SESSION PARAMETERS"
#define OPENSSL_PEM_STRING_X509 "CERTIFICATE"
#define OPENSSL_PEM_STRING_X509_CRL "X509 CRL"
#define OPENSSL_PEM_STRING_X509_OLD "X509 CERTIFICATE"
#define OPENSSL_PEM_STRING_X509_REQ "CERTIFICATE REQUEST"
#define OPENSSL_PEM_STRING_X509_REQ_OLD "NEW CERTIFICATE REQUEST"
static const Shortint OPENSSL_PEM_TYPE_CLEAR = 0x28;
static const Shortint OPENSSL_PEM_TYPE_ENCRYPTED = 0xa;
static const Shortint OPENSSL_PEM_TYPE_MIC_CLEAR = 0x1e;
static const Shortint OPENSSL_PEM_TYPE_MIC_ONLY = 0x14;
static const Word OPENSSL_PKCS5_DEFAULT_ITER = 0x800;
static const Shortint OPENSSL_PKCS5_SALT_LEN = 0x8;
static const Shortint OPENSSL_PKCS7_F_PKCS7_ADD_CERTIFICATE = 0x64;
static const Shortint OPENSSL_PKCS7_F_PKCS7_ADD_CRL = 0x65;
static const Shortint OPENSSL_PKCS7_F_PKCS7_ADD_RECIPIENT_INFO = 0x66;
static const Shortint OPENSSL_PKCS7_F_PKCS7_ADD_SIGNER = 0x67;
static const Shortint OPENSSL_PKCS7_F_PKCS7_CTRL = 0x68;
static const Shortint OPENSSL_PKCS7_F_PKCS7_DATADECODE = 0x70;
static const Shortint OPENSSL_PKCS7_F_PKCS7_DATAINIT = 0x69;
static const Shortint OPENSSL_PKCS7_F_PKCS7_DATASIGN = 0x6a;
static const Shortint OPENSSL_PKCS7_F_PKCS7_DATAVERIFY = 0x6b;
static const Shortint OPENSSL_PKCS7_F_PKCS7_SET_CIPHER = 0x6c;
static const Shortint OPENSSL_PKCS7_F_PKCS7_SET_CONTENT = 0x6d;
static const Shortint OPENSSL_PKCS7_F_PKCS7_SET_TYPE = 0x6e;
static const Shortint OPENSSL_PKCS7_F_PKCS7_SIGNATUREVERIFY = 0x71;
static const Shortint OPENSSL_PKCS7_OP_GET_DETACHED_SIGNATURE = 0x2;
static const Shortint OPENSSL_PKCS7_OP_SET_DETACHED_SIGNATURE = 0x1;
static const Shortint OPENSSL_PKCS7_R_CIPHER_NOT_INITIALIZED = 0x74;
static const Shortint OPENSSL_PKCS7_R_DECRYPTED_KEY_IS_WRONG_LENGTH = 0x64;
static const Shortint OPENSSL_PKCS7_R_DIGEST_FAILURE = 0x65;
static const Shortint OPENSSL_PKCS7_R_INTERNAL_ERROR = 0x66;
static const Shortint OPENSSL_PKCS7_R_MISSING_CERIPEND_INFO = 0x67;
static const Shortint OPENSSL_PKCS7_R_NO_RECIPIENT_MATCHES_CERTIFICATE = 0x73;
static const Shortint OPENSSL_PKCS7_R_OPERATION_NOT_SUPPORTED_ON_THIS_TYPE = 0x68;
static const Shortint OPENSSL_PKCS7_R_SIGNATURE_FAILURE = 0x69;
static const Shortint OPENSSL_PKCS7_R_UNABLE_TO_FIND_CERTIFICATE = 0x6a;
static const Shortint OPENSSL_PKCS7_R_UNABLE_TO_FIND_MEM_BIO = 0x6b;
static const Shortint OPENSSL_PKCS7_R_UNABLE_TO_FIND_MESSAGE_DIGEST = 0x6c;
static const Shortint OPENSSL_PKCS7_R_UNKNOWN_DIGEST_TYPE = 0x6d;
static const Shortint OPENSSL_PKCS7_R_UNKNOWN_OPERATION = 0x6e;
static const Shortint OPENSSL_PKCS7_R_UNSUPPORTED_CIPHER_TYPE = 0x6f;
static const Shortint OPENSSL_PKCS7_R_UNSUPPORTED_CONTENT_TYPE = 0x70;
static const Shortint OPENSSL_PKCS7_R_WRONG_CONTENT_TYPE = 0x71;
static const Shortint OPENSSL_PKCS7_R_WRONG_PKCS7_TYPE = 0x72;
static const Shortint OPENSSL_PKCS7_S_BODY = 0x1;
static const Shortint OPENSSL_PKCS7_S_HEADER = 0x0;
static const Shortint OPENSSL_PKCS7_S_TAIL = 0x2;
static const Shortint OPENSSL_PKCS8_NO_OCTET = 0x1;
static const Shortint OPENSSL_PKCS8_OK = 0x0;
#define OPENSSL_P_tmpdir "/tmp"
static const int OPENSSL_MSS_RAND_MAX = 0x7fffffff;
static const Shortint OPENSSL_RC2_BLOCK = 0x8;
static const Shortint OPENSSL_RC2_DECRYPT = 0x0;
static const Shortint OPENSSL_RC2_ENCRYPT = 0x1;
static const Shortint OPENSSL_RC2_KEY_LENGTH = 0x10;
static const Shortint OPENSSL_RC5_12_ROUNDS = 0xc;
static const Shortint OPENSSL_RC5_16_ROUNDS = 0x10;
static const Shortint OPENSSL_RC5_32_BLOCK = 0x8;
static const Shortint OPENSSL_RC5_32_KEY_LENGTH = 0x10;
static const Shortint OPENSSL_RC5_8_ROUNDS = 0x8;
static const Shortint OPENSSL_RC5_DECRYPT = 0x0;
static const Shortint OPENSSL_RC5_ENCRYPT = 0x1;
static const Shortint OPENSSL_RIPEMD160_BLOCK = 0x10;
static const Shortint OPENSSL_RIPEMD160_CBLOCK = 0x40;
static const Shortint OPENSSL_RIPEMD160_DIGEST_LENGTH = 0x14;
static const Shortint OPENSSL_RIPEMD160_LAST_BLOCK = 0x38;
static const Shortint OPENSSL_RIPEMD160_LBLOCK = 0x10;
static const Shortint OPENSSL_RIPEMD160_LENGTH_BLOCK = 0x8;
static const Shortint OPENSSL_RSA_3 = 0x3;
static const int OPENSSL_RSA_F4 = 0x10001;
static const Shortint OPENSSL_RSA_FLAG_BLINDING = 0x8;
static const Shortint OPENSSL_RSA_FLAG_CACHE_PRIVATE = 0x4;
static const Shortint OPENSSL_RSA_FLAG_CACHE_PUBLIC = 0x2;
static const Shortint OPENSSL_RSA_FLAG_EXT_PKEY = 0x20;
static const Shortint OPENSSL_RSA_FLAG_THREAD_SAFE = 0x10;
static const Shortint OPENSSL_RSA_F_MEMORY_LOCK = 0x64;
static const Shortint OPENSSL_RSA_F_RSA_CHECK_KEY = 0x7b;
static const Shortint OPENSSL_RSA_F_RSA_EAY_PRIVATE_DECRYPT = 0x65;
static const Shortint OPENSSL_RSA_F_RSA_EAY_PRIVATE_ENCRYPT = 0x66;
static const Shortint OPENSSL_RSA_F_RSA_EAY_PUBLIC_DECRYPT = 0x67;
static const Shortint OPENSSL_RSA_F_RSA_EAY_PUBLIC_ENCRYPT = 0x68;
static const Shortint OPENSSL_RSA_F_RSA_GENERATE_KEY = 0x69;
static const Shortint OPENSSL_RSA_F_RSA_NEW_METHOD = 0x6a;
static const Shortint OPENSSL_RSA_F_RSA_PADDING_ADD_NONE = 0x6b;
static const Shortint OPENSSL_RSA_F_RSA_PADDING_ADD_PKCS1_OAEP = 0x79;
static const Shortint OPENSSL_RSA_F_RSA_PADDING_ADD_PKCS1_TYPE_1 = 0x6c;
static const Shortint OPENSSL_RSA_F_RSA_PADDING_ADD_PKCS1_TYPE_2 = 0x6d;
static const Shortint OPENSSL_RSA_F_RSA_PADDING_ADD_SSLV23 = 0x6e;
static const Shortint OPENSSL_RSA_F_RSA_PADDING_CHECK_NONE = 0x6f;
static const Shortint OPENSSL_RSA_F_RSA_PADDING_CHECK_PKCS1_OAEP = 0x7a;
static const Shortint OPENSSL_RSA_F_RSA_PADDING_CHECK_PKCS1_TYPE_1 = 0x70;
static const Shortint OPENSSL_RSA_F_RSA_PADDING_CHECK_PKCS1_TYPE_2 = 0x71;
static const Shortint OPENSSL_RSA_F_RSA_PADDING_CHECK_SSLV23 = 0x72;
static const Shortint OPENSSL_RSA_F_RSA_PRINT = 0x73;
static const Shortint OPENSSL_RSA_F_RSA_PRINT_FP = 0x74;
static const Shortint OPENSSL_RSA_F_RSA_SIGN = 0x75;
static const Shortint OPENSSL_RSA_F_RSA_SIGN_ASN1_OCTET_STRING = 0x76;
static const Shortint OPENSSL_RSA_F_RSA_VERIFY = 0x77;
static const Shortint OPENSSL_RSA_F_RSA_VERIFY_ASN1_OCTET_STRING = 0x78;
static const Shortint OPENSSL_RSA_METHOD_FLAG_NO_CHECK = 0x1;
static const Shortint OPENSSL_RSA_NO_PADDING = 0x3;
static const Shortint OPENSSL_RSA_PKCS1_OAEP_PADDING = 0x4;
static const Shortint OPENSSL_RSA_PKCS1_PADDING = 0x1;
static const Shortint OPENSSL_RSA_R_ALGORITHM_MISMATCH = 0x64;
static const Shortint OPENSSL_RSA_R_BAD_E_VALUE = 0x65;
static const Shortint OPENSSL_RSA_R_BAD_FIXED_HEADER_DECRYPT = 0x66;
static const Shortint OPENSSL_RSA_R_BAD_PAD_BYTE_COUNT = 0x67;
static const Shortint OPENSSL_RSA_R_BAD_SIGNATURE = 0x68;
static const Shortint OPENSSL_RSA_R_BLOCK_TYPE_IS_NOT_01 = 0x6a;
static const Shortint OPENSSL_RSA_R_BLOCK_TYPE_IS_NOT_02 = 0x6b;
static const Shortint OPENSSL_RSA_R_DATA_GREATER_THAN_MOD_LEN = 0x6c;
static const Shortint OPENSSL_RSA_R_DATA_TOO_LARGE = 0x6d;
static const Shortint OPENSSL_RSA_R_DATA_TOO_LARGE_FOR_KEY_SIZE = 0x6e;
static const Shortint OPENSSL_RSA_R_DATA_TOO_SMALL = 0x6f;
static const Shortint OPENSSL_RSA_R_DATA_TOO_SMALL_FOR_KEY_SIZE = 0x7a;
static const Shortint OPENSSL_RSA_R_DIGEST_TOO_BIG_FOR_RSA_KEY = 0x70;
static const Shortint OPENSSL_RSA_R_DMP1_NOT_CONGRUENT_TO_D = 0x7c;
static const Shortint OPENSSL_RSA_R_DMQ1_NOT_CONGRUENT_TO_D = 0x7d;
static const Shortint OPENSSL_RSA_R_D_E_NOT_CONGRUENT_TO_1 = 0x7b;
static const Shortint OPENSSL_RSA_R_IQMP_NOT_INVERSE_OF_Q = 0x7e;
static const Shortint OPENSSL_RSA_R_KEY_SIZE_TOO_SMALL = 0x78;
static const Shortint OPENSSL_RSA_R_NULL_BEFORE_BLOCK_MISSING = 0x71;
static const Shortint OPENSSL_RSA_R_N_DOES_NOT_EQUAL_P_Q = 0x7f;
static const Shortint OPENSSL_RSA_R_OAEP_DECODING_ERROR = 0x79;
static const Shortint OPENSSL_RSA_R_PADDING_CHECK_FAILED = 0x72;
static const System::Byte OPENSSL_RSA_R_P_NOT_PRIME = 0x80;
static const System::Byte OPENSSL_RSA_R_Q_NOT_PRIME = 0x81;
static const Shortint OPENSSL_RSA_R_SSLV3_ROLLBACK_ATTACK = 0x73;
static const Shortint OPENSSL_RSA_R_THE_ASN1_OBJECT_IDENTIFIER_IS_NOT_KNOWN_FOR_THIS_MD = 0x74;
static const Shortint OPENSSL_RSA_R_UNKNOWN_ALGORITHM_TYPE = 0x75;
static const Shortint OPENSSL_RSA_R_UNKNOWN_PADDING_TYPE = 0x76;
static const Shortint OPENSSL_RSA_R_WRONG_SIGNATURE_LENGTH = 0x77;
static const Shortint OPENSSL_RSA_SSLV23_PADDING = 0x2;
static const Shortint OPENSSL_SEEK_CUR = 0x1;
static const Shortint OPENSSL_SEEK_END = 0x2;
static const Shortint OPENSSL_SEEK_SET = 0x0;
static const Shortint OPENSSL_SHA_DIGEST_LENGTH = 0x14;
static const Shortint OPENSSL_SHA_LBLOCK = 0x10;
#define OPENSSL_SN_Algorithm "Algorithm"
#define OPENSSL_SN_SMIMECapabilities "SMIME-CAPS"
#define OPENSSL_SN_authority_key_identifier "authorityKeyIdentifier"
#define OPENSSL_SN_basic_constraints "basicConstraints"
#define OPENSSL_SN_bf_cbc "BF-CBC"
#define OPENSSL_SN_bf_cfb64 "BF-CFB"
#define OPENSSL_SN_bf_ecb "BF-ECB"
#define OPENSSL_SN_bf_ofb64 "BF-OFB"
#define OPENSSL_SN_cast5_cbc "CAST5-CBC"
#define OPENSSL_SN_cast5_cfb64 "CAST5-CFB"
#define OPENSSL_SN_cast5_ecb "CAST5-ECB"
#define OPENSSL_SN_cast5_ofb64 "CAST5-OFB"
#define OPENSSL_SN_certificate_policies "certificatePolicies"
#define OPENSSL_SN_client_auth "clientAuth"
#define OPENSSL_SN_code_sign "codeSigning"
#define OPENSSL_SN_commonName "CN"
static const char OPENSSL_SN_countryName = '\x43';
#define OPENSSL_SN_crl_distribution_points "crlDistributionPoints"
#define OPENSSL_SN_crl_number "crlNumber"
#define OPENSSL_SN_crl_reason "CRLReason"
#define OPENSSL_SN_delta_crl "deltaCRL"
#define OPENSSL_SN_des_cbc "DES-CBC"
#define OPENSSL_SN_des_cfb64 "DES-CFB"
#define OPENSSL_SN_des_ecb "DES-ECB"
#define OPENSSL_SN_des_ede "DES-EDE"
#define OPENSSL_SN_des_ede3 "DES-EDE3"
#define OPENSSL_SN_des_ede3_cbc "DES-EDE3-CBC"
#define OPENSSL_SN_des_ede3_cfb64 "DES-EDE3-CFB"
#define OPENSSL_SN_des_ede3_ofb64 "DES-EDE3-OFB"
#define OPENSSL_SN_des_ede_cbc "DES-EDE-CBC"
#define OPENSSL_SN_des_ede_cfb64 "DES-EDE-CFB"
#define OPENSSL_SN_des_ede_ofb64 "DES-EDE-OFB"
#define OPENSSL_SN_des_ofb64 "DES-OFB"
static const char OPENSSL_SN_description = '\x44';
#define OPENSSL_SN_desx_cbc "DESX-CBC"
#define OPENSSL_SN_dsa "DSA"
#define OPENSSL_SN_dsaWithSHA "DSA-SHA"
#define OPENSSL_SN_dsaWithSHA1 "DSA-SHA1"
#define OPENSSL_SN_dsaWithSHA1_2 "DSA-SHA1-old"
#define OPENSSL_SN_dsa_2 "DSA-old"
#define OPENSSL_SN_email_protect "emailProtection"
#define OPENSSL_SN_ext_key_usage "extendedKeyUsage"
static const char OPENSSL_SN_givenName = '\x47';
#define OPENSSL_SN_id_kp "id-kp"
#define OPENSSL_SN_id_pkix "PKIX"
#define OPENSSL_SN_id_qt_cps "id-qt-cps"
#define OPENSSL_SN_id_qt_unotice "id-qt-unotice"
#define OPENSSL_SN_idea_cbc "IDEA-CBC"
#define OPENSSL_SN_idea_cfb64 "IDEA-CFB"
#define OPENSSL_SN_idea_ecb "IDEA-ECB"
#define OPENSSL_SN_idea_ofb64 "IDEA-OFB"
static const char OPENSSL_SN_initials = '\x49';
#define OPENSSL_SN_invalidity_date "invalidityDate"
#define OPENSSL_SN_issuer_alt_name "issuerAltName"
#define OPENSSL_SN_key_usage "keyUsage"
#define OPENSSL_SN_ld_ce "ld-ce"
static const char OPENSSL_SN_localityName = '\x4c';
#define OPENSSL_SN_md2 "MD2"
#define OPENSSL_SN_md2WithRSAEncryption "RSA-MD2"
#define OPENSSL_SN_md5 "MD5"
#define OPENSSL_SN_md5WithRSA "RSA-NP-MD5"
#define OPENSSL_SN_md5WithRSAEncryption "RSA-MD5"
#define OPENSSL_SN_md5_sha1 "MD5-SHA1"
#define OPENSSL_SN_mdc2 "MDC2"
#define OPENSSL_SN_mdc2WithRSA "RSA-MDC2"
#define OPENSSL_SN_ms_code_com "msCodeCom"
#define OPENSSL_SN_ms_code_ind "msCodeInd"
#define OPENSSL_SN_ms_ctl_sign "msCTLSign"
#define OPENSSL_SN_ms_efs "msEFS"
#define OPENSSL_SN_ms_sgc "msSGC"
#define OPENSSL_SN_netscape "Netscape"
#define OPENSSL_SN_netscape_base_url "nsBaseUrl"
#define OPENSSL_SN_netscape_ca_policy_url "nsCaPolicyUrl"
#define OPENSSL_SN_netscape_ca_revocation_url "nsCaRevocationUrl"
#define OPENSSL_SN_netscape_cert_extension "nsCertExt"
#define OPENSSL_SN_netscape_cert_sequence "nsCertSequence"
#define OPENSSL_SN_netscape_cert_type "nsCertType"
#define OPENSSL_SN_netscape_comment "nsComment"
#define OPENSSL_SN_netscape_data_type "nsDataType"
#define OPENSSL_SN_netscape_renewal_url "nsRenewalUrl"
#define OPENSSL_SN_netscape_revocation_url "nsRevocationUrl"
#define OPENSSL_SN_netscape_ssl_server_name "nsSslServerName"
#define OPENSSL_SN_ns_sgc "nsSGC"
static const char OPENSSL_SN_organizationName = '\x4f';
#define OPENSSL_SN_organizationalUnitName "OU"
#define OPENSSL_SN_pkcs9_emailAddress "Email"
#define OPENSSL_SN_private_key_usage_period "privateKeyUsagePeriod"
#define OPENSSL_SN_rc2_40_cbc "RC2-40-CBC"
#define OPENSSL_SN_rc2_64_cbc "RC2-64-CBC"
#define OPENSSL_SN_rc2_cbc "RC2-CBC"
#define OPENSSL_SN_rc2_cfb64 "RC2-CFB"
#define OPENSSL_SN_rc2_ecb "RC2-ECB"
#define OPENSSL_SN_rc2_ofb64 "RC2-OFB"
#define OPENSSL_SN_rc4 "RC4"
#define OPENSSL_SN_rc4_40 "RC4-40"
#define OPENSSL_SN_rc5_cbc "RC5-CBC"
#define OPENSSL_SN_rc5_cfb64 "RC5-CFB"
#define OPENSSL_SN_rc5_ecb "RC5-ECB"
#define OPENSSL_SN_rc5_ofb64 "RC5-OFB"
#define OPENSSL_SN_ripemd160 "RIPEMD160"
#define OPENSSL_SN_ripemd160WithRSA "RSA-RIPEMD160"
#define OPENSSL_SN_rle_compression "RLE"
#define OPENSSL_SN_rsa "RSA"
#define OPENSSL_SN_serialNumber "SN"
#define OPENSSL_SN_server_auth "serverAuth"
#define OPENSSL_SN_sha "SHA"
#define OPENSSL_SN_sha1 "SHA1"
#define OPENSSL_SN_sha1WithRSA "RSA-SHA1-2"
#define OPENSSL_SN_sha1WithRSAEncryption "RSA-SHA1"
#define OPENSSL_SN_shaWithRSAEncryption "RSA-SHA"
#define OPENSSL_SN_stateOrProvinceName "ST"
#define OPENSSL_SN_subject_alt_name "subjectAltName"
#define OPENSSL_SN_subject_key_identifier "subjectKeyIdentifier"
static const char OPENSSL_SN_surname = '\x53';
#define OPENSSL_SN_sxnet "SXNetID"
#define OPENSSL_SN_time_stamp "timeStamping"
static const char OPENSSL_SN_title = '\x54';
#define OPENSSL_SN_undef "UNDEF"
#define OPENSSL_SN_uniqueIdentifier "UID"
#define OPENSSL_SN_zlib_compression "ZLIB"
static const Word OPENSSL_SSL_ST_CONNECT = 0x1000;
static const Word OPENSSL_SSL23_ST_CR_SRVR_HELLO_A = 0x1220;
static const Word OPENSSL_SSL23_ST_CR_SRVR_HELLO_B = 0x1221;
static const Word OPENSSL_SSL23_ST_CW_CLNT_HELLO_A = 0x1210;
static const Word OPENSSL_SSL23_ST_CW_CLNT_HELLO_B = 0x1211;
static const Word OPENSSL_SSL_ST_ACCEPT = 0x2000;
static const Word OPENSSL_SSL23_ST_SR_CLNT_HELLO_A = 0x2210;
static const Word OPENSSL_SSL23_ST_SR_CLNT_HELLO_B = 0x2211;
static const Shortint OPENSSL_SSL2_AT_MD5_WITH_RSA_ENCRYPTION = 0x1;
static const Shortint OPENSSL_SSL2_CF_5_BYTE_ENC = 0x1;
static const Shortint OPENSSL_SSL2_CF_8_BYTE_ENC = 0x2;
static const Shortint OPENSSL_SSL2_CHALLENGE_LENGTH = 0x10;
static const int OPENSSL_SSL2_CK_DES_192_EDE3_CBC_WITH_MD5 = 0x20700c0;
static const int OPENSSL_SSL2_CK_DES_192_EDE3_CBC_WITH_SHA = 0x20701c0;
static const int OPENSSL_SSL2_CK_DES_64_CBC_WITH_MD5 = 0x2060040;
static const int OPENSSL_SSL2_CK_DES_64_CBC_WITH_SHA = 0x2060140;
static const int OPENSSL_SSL2_CK_DES_64_CFB64_WITH_MD5_1 = 0x2ff0800;
static const int OPENSSL_SSL2_CK_IDEA_128_CBC_WITH_MD5 = 0x2050080;
static const int OPENSSL_SSL2_CK_NULL = 0x2ff0810;
static const int OPENSSL_SSL2_CK_NULL_WITH_MD5 = 0x2000000;
static const int OPENSSL_SSL2_CK_RC2_128_CBC_EXPORT40_WITH_MD5 = 0x2040080;
static const int OPENSSL_SSL2_CK_RC2_128_CBC_WITH_MD5 = 0x2030080;
static const int OPENSSL_SSL2_CK_RC4_128_EXPORT40_WITH_MD5 = 0x2020080;
static const int OPENSSL_SSL2_CK_RC4_128_WITH_MD5 = 0x2010080;
static const int OPENSSL_SSL2_CK_RC4_64_WITH_MD5 = 0x2080080;
static const Shortint OPENSSL_SSL2_CONNECTION_ID_LENGTH = 0x10;
static const Shortint OPENSSL_SSL2_CT_X509_CERTIFICATE = 0x1;
static const Shortint OPENSSL_SSL2_MAX_CERT_CHALLENGE_LENGTH = 0x20;
static const Shortint OPENSSL_SSL2_MAX_CHALLENGE_LENGTH = 0x20;
static const Shortint OPENSSL_SSL2_MAX_CONNECTION_ID_LENGTH = 0x10;
static const Shortint OPENSSL_SSL2_MAX_KEY_MATERIAL_LENGTH = 0x18;
static const Word OPENSSL_SSL2_MAX_MASTER_KEY_LENGTH_IN_BITS = 0x100;
static const Word OPENSSL_SSL2_MAX_RECORD_LENGTH_3_BYTE_HEADER = 0x3fff;
static const Shortint OPENSSL_SSL2_MAX_SSL_SESSION_ID_LENGTH = 0x20;
static const Shortint OPENSSL_SSL2_MIN_CERT_CHALLENGE_LENGTH = 0x10;
static const Shortint OPENSSL_SSL2_MIN_CHALLENGE_LENGTH = 0x10;
static const Shortint OPENSSL_SSL2_MT_CLIENT_CERTIFICATE = 0x8;
static const Shortint OPENSSL_SSL2_MT_CLIENT_FINISHED = 0x3;
static const Shortint OPENSSL_SSL2_MT_CLIENT_HELLO = 0x1;
static const Shortint OPENSSL_SSL2_MT_CLIENT_MASTER_KEY = 0x2;
static const Shortint OPENSSL_SSL2_MT_ERROR = 0x0;
static const Shortint OPENSSL_SSL2_MT_REQUEST_CERTIFICATE = 0x7;
static const Shortint OPENSSL_SSL2_MT_SERVER_FINISHED = 0x6;
static const Shortint OPENSSL_SSL2_MT_SERVER_HELLO = 0x4;
static const Shortint OPENSSL_SSL2_MT_SERVER_VERIFY = 0x5;
static const Shortint OPENSSL_SSL2_PE_BAD_CERTIFICATE = 0x4;
static const Shortint OPENSSL_SSL2_PE_NO_CERTIFICATE = 0x2;
static const Shortint OPENSSL_SSL2_PE_NO_CIPHER = 0x1;
static const Shortint OPENSSL_SSL2_PE_UNDEFINED_ERROR = 0x0;
static const Shortint OPENSSL_SSL2_PE_UNSUPPORTED_CERTIFICATE_TYPE = 0x6;
static const Shortint OPENSSL_SSL2_SSL_SESSION_ID_LENGTH = 0x10;
static const Word OPENSSL_SSL2_ST_CLIENT_START_ENCRYPTION = 0x1080;
static const Word OPENSSL_SSL2_ST_GET_CLIENT_FINISHED_A = 0x2050;
static const Word OPENSSL_SSL2_ST_GET_CLIENT_FINISHED_B = 0x2051;
static const Word OPENSSL_SSL2_ST_GET_CLIENT_HELLO_A = 0x2010;
static const Word OPENSSL_SSL2_ST_GET_CLIENT_HELLO_B = 0x2011;
static const Word OPENSSL_SSL2_ST_GET_CLIENT_HELLO_C = 0x2012;
static const Word OPENSSL_SSL2_ST_GET_CLIENT_MASTER_KEY_A = 0x2030;
static const Word OPENSSL_SSL2_ST_GET_CLIENT_MASTER_KEY_B = 0x2031;
static const Word OPENSSL_SSL2_ST_GET_SERVER_FINISHED_A = 0x1070;
static const Word OPENSSL_SSL2_ST_GET_SERVER_FINISHED_B = 0x1071;
static const Word OPENSSL_SSL2_ST_GET_SERVER_HELLO_A = 0x1020;
static const Word OPENSSL_SSL2_ST_GET_SERVER_HELLO_B = 0x1021;
static const Word OPENSSL_SSL2_ST_GET_SERVER_VERIFY_A = 0x1060;
static const Word OPENSSL_SSL2_ST_GET_SERVER_VERIFY_B = 0x1061;
static const Word OPENSSL_SSL2_ST_SEND_CLIENT_CERTIFICATE_A = 0x1050;
static const Word OPENSSL_SSL2_ST_SEND_CLIENT_CERTIFICATE_B = 0x1051;
static const Word OPENSSL_SSL2_ST_SEND_CLIENT_CERTIFICATE_C = 0x1052;
static const Word OPENSSL_SSL2_ST_SEND_CLIENT_CERTIFICATE_D = 0x1053;
static const Word OPENSSL_SSL2_ST_SEND_CLIENT_FINISHED_A = 0x1040;
static const Word OPENSSL_SSL2_ST_SEND_CLIENT_FINISHED_B = 0x1041;
static const Word OPENSSL_SSL2_ST_SEND_CLIENT_HELLO_A = 0x1010;
static const Word OPENSSL_SSL2_ST_SEND_CLIENT_HELLO_B = 0x1011;
static const Word OPENSSL_SSL2_ST_SEND_CLIENT_MASTER_KEY_A = 0x1030;
static const Word OPENSSL_SSL2_ST_SEND_CLIENT_MASTER_KEY_B = 0x1031;
static const Word OPENSSL_SSL2_ST_SEND_REQUEST_CERTIFICATE_A = 0x2070;
static const Word OPENSSL_SSL2_ST_SEND_REQUEST_CERTIFICATE_B = 0x2071;
static const Word OPENSSL_SSL2_ST_SEND_REQUEST_CERTIFICATE_C = 0x2072;
static const Word OPENSSL_SSL2_ST_SEND_REQUEST_CERTIFICATE_D = 0x2073;
static const Word OPENSSL_SSL2_ST_SEND_SERVER_FINISHED_A = 0x2060;
static const Word OPENSSL_SSL2_ST_SEND_SERVER_FINISHED_B = 0x2061;
static const Word OPENSSL_SSL2_ST_SEND_SERVER_HELLO_A = 0x2020;
static const Word OPENSSL_SSL2_ST_SEND_SERVER_HELLO_B = 0x2021;
static const Word OPENSSL_SSL2_ST_SEND_SERVER_VERIFY_A = 0x2040;
static const Word OPENSSL_SSL2_ST_SEND_SERVER_VERIFY_B = 0x2041;
static const Word OPENSSL_SSL2_ST_SEND_SERVER_VERIFY_C = 0x2042;
static const Word OPENSSL_SSL2_ST_SERVER_START_ENCRYPTION = 0x2080;
static const Word OPENSSL_SSL2_ST_X509_GET_CLIENT_CERTIFICATE = 0x1090;
static const Word OPENSSL_SSL2_ST_X509_GET_SERVER_CERTIFICATE = 0x2090;
#define OPENSSL_SSL2_TXT_DES_192_EDE3_CBC_WITH_MD5 "DES-CBC3-MD5"
#define OPENSSL_SSL2_TXT_DES_192_EDE3_CBC_WITH_SHA "DES-CBC3-SHA"
#define OPENSSL_SSL2_TXT_DES_64_CBC_WITH_MD5 "DES-CBC-MD5"
#define OPENSSL_SSL2_TXT_DES_64_CBC_WITH_SHA "DES-CBC-SHA"
#define OPENSSL_SSL2_TXT_DES_64_CFB64_WITH_MD5_1 "DES-CFB-M1"
#define OPENSSL_SSL2_TXT_IDEA_128_CBC_WITH_MD5 "IDEA-CBC-MD5"
#define OPENSSL_SSL2_TXT_NULL "NULL"
#define OPENSSL_SSL2_TXT_NULL_WITH_MD5 "NULL-MD5"
#define OPENSSL_SSL2_TXT_RC2_128_CBC_EXPORT40_WITH_MD5 "EXP-RC2-CBC-MD5"
#define OPENSSL_SSL2_TXT_RC2_128_CBC_WITH_MD5 "RC2-CBC-MD5"
#define OPENSSL_SSL2_TXT_RC4_128_EXPORT40_WITH_MD5 "EXP-RC4-MD5"
#define OPENSSL_SSL2_TXT_RC4_128_WITH_MD5 "RC4-MD5"
#define OPENSSL_SSL2_TXT_RC4_64_WITH_MD5 "RC4-64-MD5"
static const Shortint OPENSSL_SSL2_VERSION = 0x2;
static const Shortint OPENSSL_SSL2_VERSION_MAJOR = 0x0;
static const Shortint OPENSSL_SSL2_VERSION_MINOR = 0x2;
static const Shortint OPENSSL_SSL3_AD_BAD_CERTIFICATE = 0x2a;
static const Shortint OPENSSL_SSL3_AD_BAD_RECORD_MAC = 0x14;
static const Shortint OPENSSL_SSL3_AD_CERTIFICATE_EXPIRED = 0x2d;
static const Shortint OPENSSL_SSL3_AD_CERTIFICATE_REVOKED = 0x2c;
static const Shortint OPENSSL_SSL3_AD_CERTIFICATE_UNKNOWN = 0x2e;
static const Shortint OPENSSL_SSL3_AD_CLOSE_NOTIFY = 0x0;
static const Shortint OPENSSL_SSL3_AD_DECOMPRESSION_FAILURE = 0x1e;
static const Shortint OPENSSL_SSL3_AD_HANDSHAKE_FAILURE = 0x28;
static const Shortint OPENSSL_SSL3_AD_ILLEGAL_PARAMETER = 0x2f;
static const Shortint OPENSSL_SSL3_AD_NO_CERTIFICATE = 0x29;
static const Shortint OPENSSL_SSL3_AD_UNEXPECTED_MESSAGE = 0xa;
static const Shortint OPENSSL_SSL3_AD_UNSUPPORTED_CERTIFICATE = 0x2b;
static const Shortint OPENSSL_SSL3_AL_FATAL = 0x2;
static const Shortint OPENSSL_SSL3_AL_WARNING = 0x1;
static const Shortint OPENSSL_SSL3_CC_CLIENT = 0x10;
static const Shortint OPENSSL_SSL3_CC_READ = 0x1;
static const Shortint OPENSSL_SSL3_CC_SERVER = 0x20;
static const Shortint OPENSSL_SSL3_CC_WRITE = 0x2;
static const Shortint OPENSSL_SSL3_CHANGE_CIPHER_CLIENT_READ = 0x11;
static const Shortint OPENSSL_SSL3_CHANGE_CIPHER_CLIENT_WRITE = 0x12;
static const Shortint OPENSSL_SSL3_CHANGE_CIPHER_SERVER_READ = 0x21;
static const Shortint OPENSSL_SSL3_CHANGE_CIPHER_SERVER_WRITE = 0x22;
static const int OPENSSL_SSL3_CK_ADH_DES_192_CBC_SHA = 0x300001b;
static const int OPENSSL_SSL3_CK_ADH_DES_40_CBC_SHA = 0x3000019;
static const int OPENSSL_SSL3_CK_ADH_DES_64_CBC_SHA = 0x300001a;
static const int OPENSSL_SSL3_CK_ADH_RC4_128_MD5 = 0x3000018;
static const int OPENSSL_SSL3_CK_ADH_RC4_40_MD5 = 0x3000017;
static const int OPENSSL_SSL3_CK_DH_DSS_DES_192_CBC3_SHA = 0x300000d;
static const int OPENSSL_SSL3_CK_DH_DSS_DES_40_CBC_SHA = 0x300000b;
static const int OPENSSL_SSL3_CK_DH_DSS_DES_64_CBC_SHA = 0x300000c;
static const int OPENSSL_SSL3_CK_DH_RSA_DES_192_CBC3_SHA = 0x3000010;
static const int OPENSSL_SSL3_CK_DH_RSA_DES_40_CBC_SHA = 0x300000e;
static const int OPENSSL_SSL3_CK_DH_RSA_DES_64_CBC_SHA = 0x300000f;
static const int OPENSSL_SSL3_CK_EDH_DSS_DES_192_CBC3_SHA = 0x3000013;
static const int OPENSSL_SSL3_CK_EDH_DSS_DES_40_CBC_SHA = 0x3000011;
static const int OPENSSL_SSL3_CK_EDH_DSS_DES_64_CBC_SHA = 0x3000012;
static const int OPENSSL_SSL3_CK_EDH_RSA_DES_192_CBC3_SHA = 0x3000016;
static const int OPENSSL_SSL3_CK_EDH_RSA_DES_40_CBC_SHA = 0x3000014;
static const int OPENSSL_SSL3_CK_EDH_RSA_DES_64_CBC_SHA = 0x3000015;
static const int OPENSSL_SSL3_CK_FZA_DMS_FZA_SHA = 0x300001d;
static const int OPENSSL_SSL3_CK_FZA_DMS_NULL_SHA = 0x300001c;
static const int OPENSSL_SSL3_CK_FZA_DMS_RC4_SHA = 0x300001e;
static const int OPENSSL_SSL3_CK_RSA_DES_192_CBC3_SHA = 0x300000a;
static const int OPENSSL_SSL3_CK_RSA_DES_40_CBC_SHA = 0x3000008;
static const int OPENSSL_SSL3_CK_RSA_DES_64_CBC_SHA = 0x3000009;
static const int OPENSSL_SSL3_CK_RSA_IDEA_128_SHA = 0x3000007;
static const int OPENSSL_SSL3_CK_RSA_NULL_MD5 = 0x3000001;
static const int OPENSSL_SSL3_CK_RSA_NULL_SHA = 0x3000002;
static const int OPENSSL_SSL3_CK_RSA_RC2_40_MD5 = 0x3000006;
static const int OPENSSL_SSL3_CK_RSA_RC4_128_MD5 = 0x3000004;
static const int OPENSSL_SSL3_CK_RSA_RC4_128_SHA = 0x3000005;
static const int OPENSSL_SSL3_CK_RSA_RC4_40_MD5 = 0x3000003;
static const Shortint OPENSSL_SSL3_CT_DSS_EPHEMERAL_DH = 0x6;
static const Shortint OPENSSL_SSL3_CT_DSS_FIXED_DH = 0x4;
static const Shortint OPENSSL_SSL3_CT_DSS_SIGN = 0x2;
static const Shortint OPENSSL_SSL3_CT_FORTEZZA_DMS = 0x14;
static const Shortint OPENSSL_SSL3_CT_NUMBER = 0x7;
static const Shortint OPENSSL_SSL3_CT_RSA_EPHEMERAL_DH = 0x5;
static const Shortint OPENSSL_SSL3_CT_RSA_FIXED_DH = 0x3;
static const Shortint OPENSSL_SSL3_CT_RSA_SIGN = 0x1;
static const Shortint OPENSSL_SSL3_FLAGS_DELAY_CLIENT_FINISHED = 0x2;
static const Shortint OPENSSL_SSL3_FLAGS_NO_RENEGOTIATE_CIPHERS = 0x1;
static const Shortint OPENSSL_SSL3_FLAGS_POP_BUFFER = 0x4;
static const Shortint OPENSSL_SSL3_MASTER_SECRET_SIZE = 0x30;
static const Shortint OPENSSL_SSL3_MAX_SSL_SESSION_ID_LENGTH = 0x20;
static const Shortint OPENSSL_SSL3_MT_CCS = 0x1;
static const Shortint OPENSSL_SSL3_MT_CERTIFICATE = 0xb;
static const Shortint OPENSSL_SSL3_MT_CERTIFICATE_REQUEST = 0xd;
static const Shortint OPENSSL_SSL3_MT_CERTIFICATE_VERIFY = 0xf;
static const Shortint OPENSSL_SSL3_MT_CLIENT_HELLO = 0x1;
static const Shortint OPENSSL_SSL3_MT_CLIENT_KEY_EXCHANGE = 0x10;
static const Shortint OPENSSL_SSL3_MT_CLIENT_REQUEST = 0x0;
static const Shortint OPENSSL_SSL3_MT_FINISHED = 0x14;
static const Shortint OPENSSL_SSL3_MT_SERVER_DONE = 0xe;
static const Shortint OPENSSL_SSL3_MT_SERVER_HELLO = 0x2;
static const Shortint OPENSSL_SSL3_MT_SERVER_KEY_EXCHANGE = 0xc;
static const Shortint OPENSSL_SSL3_RANDOM_SIZE = 0x20;
static const Shortint OPENSSL_SSL3_RS_BLANK = 0x1;
static const Shortint OPENSSL_SSL3_RS_ENCODED = 0x2;
static const Shortint OPENSSL_SSL3_RS_PART_READ = 0x4;
static const Shortint OPENSSL_SSL3_RS_PART_WRITE = 0x5;
static const Shortint OPENSSL_SSL3_RS_PLAIN = 0x3;
static const Shortint OPENSSL_SSL3_RS_READ_MORE = 0x3;
static const Shortint OPENSSL_SSL3_RT_ALERT = 0x15;
static const Shortint OPENSSL_SSL3_RT_APPLICATION_DATA = 0x17;
static const Shortint OPENSSL_SSL3_RT_CHANGE_CIPHER_SPEC = 0x14;
static const Shortint OPENSSL_SSL3_RT_HANDSHAKE = 0x16;
static const Shortint OPENSSL_SSL3_RT_HEADER_LENGTH = 0x5;
static const Word OPENSSL_SSL3_RT_MAX_PLAIN_LENGTH = 0x4000;
static const Word OPENSSL_SSL3_RT_MAX_COMPRESSED_LENGTH = 0x4400;
static const int OPENSSL_SSL3_RT_MAX_DATA_SIZE = 0x100000;
static const Word OPENSSL_SSL3_RT_MAX_ENCRYPTED_LENGTH = 0x4800;
static const Word OPENSSL_SSL3_RT_MAX_EXTRA = 0x4000;
static const Word OPENSSL_SSL3_RT_MAX_PACKET_SIZE = 0x4805;
static const Shortint OPENSSL_SSL3_SESSION_ID_SIZE = 0x20;
static const Shortint OPENSSL_SSL3_SSL_SESSION_ID_LENGTH = 0x20;
static const Word OPENSSL_SSL3_ST_CR_CERT_A = 0x1130;
static const Word OPENSSL_SSL3_ST_CR_CERT_B = 0x1131;
static const Word OPENSSL_SSL3_ST_CR_CERT_REQ_A = 0x1150;
static const Word OPENSSL_SSL3_ST_CR_CERT_REQ_B = 0x1151;
static const Word OPENSSL_SSL3_ST_CR_CHANGE_A = 0x11c0;
static const Word OPENSSL_SSL3_ST_CR_CHANGE_B = 0x11c1;
static const Word OPENSSL_SSL3_ST_CR_FINISHED_A = 0x11d0;
static const Word OPENSSL_SSL3_ST_CR_FINISHED_B = 0x11d1;
static const Word OPENSSL_SSL3_ST_CR_KEY_EXCH_A = 0x1140;
static const Word OPENSSL_SSL3_ST_CR_KEY_EXCH_B = 0x1141;
static const Word OPENSSL_SSL3_ST_CR_SRVR_DONE_A = 0x1160;
static const Word OPENSSL_SSL3_ST_CR_SRVR_DONE_B = 0x1161;
static const Word OPENSSL_SSL3_ST_CR_SRVR_HELLO_A = 0x1120;
static const Word OPENSSL_SSL3_ST_CR_SRVR_HELLO_B = 0x1121;
static const Word OPENSSL_SSL3_ST_CW_CERT_A = 0x1170;
static const Word OPENSSL_SSL3_ST_CW_CERT_B = 0x1171;
static const Word OPENSSL_SSL3_ST_CW_CERT_C = 0x1172;
static const Word OPENSSL_SSL3_ST_CW_CERT_D = 0x1173;
static const Word OPENSSL_SSL3_ST_CW_CERT_VRFY_A = 0x1190;
static const Word OPENSSL_SSL3_ST_CW_CERT_VRFY_B = 0x1191;
static const Word OPENSSL_SSL3_ST_CW_CHANGE_A = 0x11a0;
static const Word OPENSSL_SSL3_ST_CW_CHANGE_B = 0x11a1;
static const Word OPENSSL_SSL3_ST_CW_CLNT_HELLO_A = 0x1110;
static const Word OPENSSL_SSL3_ST_CW_CLNT_HELLO_B = 0x1111;
static const Word OPENSSL_SSL3_ST_CW_FINISHED_A = 0x11b0;
static const Word OPENSSL_SSL3_ST_CW_FINISHED_B = 0x11b1;
static const Word OPENSSL_SSL3_ST_CW_FLUSH = 0x1100;
static const Word OPENSSL_SSL3_ST_CW_KEY_EXCH_A = 0x1180;
static const Word OPENSSL_SSL3_ST_CW_KEY_EXCH_B = 0x1181;
static const Word OPENSSL_SSL3_ST_SR_CERT_A = 0x2180;
static const Word OPENSSL_SSL3_ST_SR_CERT_B = 0x2181;
static const Word OPENSSL_SSL3_ST_SR_CERT_VRFY_A = 0x21a0;
static const Word OPENSSL_SSL3_ST_SR_CERT_VRFY_B = 0x21a1;
static const Word OPENSSL_SSL3_ST_SR_CHANGE_A = 0x21b0;
static const Word OPENSSL_SSL3_ST_SR_CHANGE_B = 0x21b1;
static const Word OPENSSL_SSL3_ST_SR_CLNT_HELLO_A = 0x2110;
static const Word OPENSSL_SSL3_ST_SR_CLNT_HELLO_B = 0x2111;
static const Word OPENSSL_SSL3_ST_SR_CLNT_HELLO_C = 0x2112;
static const Word OPENSSL_SSL3_ST_SR_FINISHED_A = 0x21c0;
static const Word OPENSSL_SSL3_ST_SR_FINISHED_B = 0x21c1;
static const Word OPENSSL_SSL3_ST_SR_KEY_EXCH_A = 0x2190;
static const Word OPENSSL_SSL3_ST_SR_KEY_EXCH_B = 0x2191;
static const Word OPENSSL_SSL3_ST_SW_CERT_A = 0x2140;
static const Word OPENSSL_SSL3_ST_SW_CERT_B = 0x2141;
static const Word OPENSSL_SSL3_ST_SW_CERT_REQ_A = 0x2160;
static const Word OPENSSL_SSL3_ST_SW_CERT_REQ_B = 0x2161;
static const Word OPENSSL_SSL3_ST_SW_CHANGE_A = 0x21d0;
static const Word OPENSSL_SSL3_ST_SW_CHANGE_B = 0x21d1;
static const Word OPENSSL_SSL3_ST_SW_FINISHED_A = 0x21e0;
static const Word OPENSSL_SSL3_ST_SW_FINISHED_B = 0x21e1;
static const Word OPENSSL_SSL3_ST_SW_FLUSH = 0x2100;
static const Word OPENSSL_SSL3_ST_SW_HELLO_REQ_A = 0x2120;
static const Word OPENSSL_SSL3_ST_SW_HELLO_REQ_B = 0x2121;
static const Word OPENSSL_SSL3_ST_SW_HELLO_REQ_C = 0x2122;
static const Word OPENSSL_SSL3_ST_SW_KEY_EXCH_A = 0x2150;
static const Word OPENSSL_SSL3_ST_SW_KEY_EXCH_B = 0x2151;
static const Word OPENSSL_SSL3_ST_SW_SRVR_DONE_A = 0x2170;
static const Word OPENSSL_SSL3_ST_SW_SRVR_DONE_B = 0x2171;
static const Word OPENSSL_SSL3_ST_SW_SRVR_HELLO_A = 0x2130;
static const Word OPENSSL_SSL3_ST_SW_SRVR_HELLO_B = 0x2131;
#define OPENSSL_SSL3_TXT_ADH_DES_192_CBC_SHA "ADH-DES-CBC3-SHA"
#define OPENSSL_SSL3_TXT_ADH_DES_40_CBC_SHA "EXP-ADH-DES-CBC-SHA"
#define OPENSSL_SSL3_TXT_ADH_DES_64_CBC_SHA "ADH-DES-CBC-SHA"
#define OPENSSL_SSL3_TXT_ADH_RC4_128_MD5 "ADH-RC4-MD5"
#define OPENSSL_SSL3_TXT_ADH_RC4_40_MD5 "EXP-ADH-RC4-MD5"
#define OPENSSL_SSL3_TXT_DH_DSS_DES_192_CBC3_SHA "DH-DSS-DES-CBC3-SHA"
#define OPENSSL_SSL3_TXT_DH_DSS_DES_40_CBC_SHA "EXP-DH-DSS-DES-CBC-SHA"
#define OPENSSL_SSL3_TXT_DH_DSS_DES_64_CBC_SHA "DH-DSS-DES-CBC-SHA"
#define OPENSSL_SSL3_TXT_DH_RSA_DES_192_CBC3_SHA "DH-RSA-DES-CBC3-SHA"
#define OPENSSL_SSL3_TXT_DH_RSA_DES_40_CBC_SHA "EXP-DH-RSA-DES-CBC-SHA"
#define OPENSSL_SSL3_TXT_DH_RSA_DES_64_CBC_SHA "DH-RSA-DES-CBC-SHA"
#define OPENSSL_SSL3_TXT_EDH_DSS_DES_192_CBC3_SHA "EDH-DSS-DES-CBC3-SHA"
#define OPENSSL_SSL3_TXT_EDH_DSS_DES_40_CBC_SHA "EXP-EDH-DSS-DES-CBC-SHA"
#define OPENSSL_SSL3_TXT_EDH_DSS_DES_64_CBC_SHA "EDH-DSS-DES-CBC-SHA"
#define OPENSSL_SSL3_TXT_EDH_RSA_DES_192_CBC3_SHA "EDH-RSA-DES-CBC3-SHA"
#define OPENSSL_SSL3_TXT_EDH_RSA_DES_40_CBC_SHA "EXP-EDH-RSA-DES-CBC-SHA"
#define OPENSSL_SSL3_TXT_EDH_RSA_DES_64_CBC_SHA "EDH-RSA-DES-CBC-SHA"
#define OPENSSL_SSL3_TXT_FZA_DMS_FZA_SHA "FZA-FZA-CBC-SHA"
#define OPENSSL_SSL3_TXT_FZA_DMS_NULL_SHA "FZA-NULL-SHA"
#define OPENSSL_SSL3_TXT_FZA_DMS_RC4_SHA "FZA-RC4-SHA"
#define OPENSSL_SSL3_TXT_RSA_DES_192_CBC3_SHA "DES-CBC3-SHA"
#define OPENSSL_SSL3_TXT_RSA_DES_40_CBC_SHA "EXP-DES-CBC-SHA"
#define OPENSSL_SSL3_TXT_RSA_DES_64_CBC_SHA "DES-CBC-SHA"
#define OPENSSL_SSL3_TXT_RSA_IDEA_128_SHA "IDEA-CBC-SHA"
#define OPENSSL_SSL3_TXT_RSA_NULL_MD5 "NULL-MD5"
#define OPENSSL_SSL3_TXT_RSA_NULL_SHA "NULL-SHA"
#define OPENSSL_SSL3_TXT_RSA_RC2_40_MD5 "EXP-RC2-CBC-MD5"
#define OPENSSL_SSL3_TXT_RSA_RC4_128_MD5 "RC4-MD5"
#define OPENSSL_SSL3_TXT_RSA_RC4_128_SHA "RC4-SHA"
#define OPENSSL_SSL3_TXT_RSA_RC4_40_MD5 "EXP-RC4-MD5"
static const Word OPENSSL_SSL3_VERSION = 0x300;
static const Shortint OPENSSL_SSL3_VERSION_MAJOR = 0x3;
static const Shortint OPENSSL_SSL3_VERSION_MINOR = 0x0;
static const Shortint OPENSSL_SSLEAY_BUILT_ON = 0x3;
static const Shortint OPENSSL_SSLEAY_CFLAGS = 0x2;
static const Shortint OPENSSL_SSLEAY_PLATFORM = 0x4;
static const Shortint OPENSSL_SSLEAY_VERSION = 0x0;
static const int OPENSSL_SSLEAY_VERSION_NUMBER = 0x904100;
static const Shortint OPENSSL_TLS1_AD_ACCESS_DENIED = 0x31;
static const Shortint OPENSSL_SSL_AD_ACCESS_DENIED = 0x31;
static const Shortint OPENSSL_SSL_AD_BAD_CERTIFICATE = 0x2a;
static const Shortint OPENSSL_SSL_AD_BAD_RECORD_MAC = 0x14;
static const Shortint OPENSSL_SSL_AD_CERTIFICATE_EXPIRED = 0x2d;
static const Shortint OPENSSL_SSL_AD_CERTIFICATE_REVOKED = 0x2c;
static const Shortint OPENSSL_SSL_AD_CERTIFICATE_UNKNOWN = 0x2e;
static const Shortint OPENSSL_SSL_AD_CLOSE_NOTIFY = 0x0;
static const Shortint OPENSSL_TLS1_AD_DECODE_ERROR = 0x32;
static const Shortint OPENSSL_SSL_AD_DECODE_ERROR = 0x32;
static const Shortint OPENSSL_SSL_AD_DECOMPRESSION_FAILURE = 0x1e;
static const Shortint OPENSSL_TLS1_AD_DECRYPTION_FAILED = 0x15;
static const Shortint OPENSSL_SSL_AD_DECRYPTION_FAILED = 0x15;
static const Shortint OPENSSL_TLS1_AD_DECRYPT_ERROR = 0x33;
static const Shortint OPENSSL_SSL_AD_DECRYPT_ERROR = 0x33;
static const Shortint OPENSSL_TLS1_AD_EXPORT_RESTRICION = 0x3c;
static const Shortint OPENSSL_SSL_AD_EXPORT_RESTRICION = 0x3c;
static const Shortint OPENSSL_SSL_AD_HANDSHAKE_FAILURE = 0x28;
static const Shortint OPENSSL_SSL_AD_ILLEGAL_PARAMETER = 0x2f;
static const Shortint OPENSSL_TLS1_AD_INSUFFICIENT_SECURITY = 0x47;
static const Shortint OPENSSL_SSL_AD_INSUFFICIENT_SECURITY = 0x47;
static const Shortint OPENSSL_TLS1_AD_INTERNAL_ERROR = 0x50;
static const Shortint OPENSSL_SSL_AD_INTERNAL_ERROR = 0x50;
static const Shortint OPENSSL_SSL_AD_NO_CERTIFICATE = 0x29;
static const Shortint OPENSSL_TLS1_AD_NO_RENEGOTIATION = 0x64;
static const Shortint OPENSSL_SSL_AD_NO_RENEGOTIATION = 0x64;
static const Shortint OPENSSL_TLS1_AD_PROTOCOL_VERSION = 0x46;
static const Shortint OPENSSL_SSL_AD_PROTOCOL_VERSION = 0x46;
static const Word OPENSSL_SSL_AD_REASON_OFFSET = 0x3e8;
static const Shortint OPENSSL_TLS1_AD_RECORD_OVERFLOW = 0x16;
static const Shortint OPENSSL_SSL_AD_RECORD_OVERFLOW = 0x16;
static const Shortint OPENSSL_SSL_AD_UNEXPECTED_MESSAGE = 0xa;
static const Shortint OPENSSL_TLS1_AD_UNKNOWN_CA = 0x30;
static const Shortint OPENSSL_SSL_AD_UNKNOWN_CA = 0x30;
static const Shortint OPENSSL_SSL_AD_UNSUPPORTED_CERTIFICATE = 0x2b;
static const Shortint OPENSSL_TLS1_AD_USER_CANCLED = 0x5a;
static const Shortint OPENSSL_SSL_AD_USER_CANCLED = 0x5a;
static const Shortint OPENSSL_SSL_CB_EXIT = 0x2;
static const Word OPENSSL_SSL_CB_ACCEPT_EXIT = 0x2002;
static const Shortint OPENSSL_SSL_CB_LOOP = 0x1;
static const Word OPENSSL_SSL_CB_ACCEPT_LOOP = 0x2001;
static const Word OPENSSL_SSL_CB_ALERT = 0x4000;
static const Word OPENSSL_SSL_CB_CONNECT_EXIT = 0x1002;
static const Word OPENSSL_SSL_CB_CONNECT_LOOP = 0x1001;
static const Shortint OPENSSL_SSL_CB_HANDSHAKE_DONE = 0x20;
static const Shortint OPENSSL_SSL_CB_HANDSHAKE_START = 0x10;
static const Shortint OPENSSL_SSL_CB_READ = 0x4;
static const Word OPENSSL_SSL_CB_READ_ALERT = 0x4004;
static const Shortint OPENSSL_SSL_CB_WRITE = 0x8;
static const Word OPENSSL_SSL_CB_WRITE_ALERT = 0x4008;
static const Shortint OPENSSL_SSL_CTRL_CLEAR_NUM_RENEGOTIATIONS = 0x9;
static const Shortint OPENSSL_SSL_CTRL_EXTRA_CHAIN_CERT = 0xc;
static const Shortint OPENSSL_SSL_CTRL_GET_CLIENT_CERT_REQUEST = 0x7;
static const Shortint OPENSSL_SSL_CTRL_GET_FLAGS = 0xb;
static const Shortint OPENSSL_SSL_CTRL_GET_NUM_RENEGOTIATIONS = 0x8;
static const Shortint OPENSSL_SSL_CTRL_GET_READ_AHEAD = 0x28;
static const Shortint OPENSSL_SSL_CTRL_GET_SESSION_REUSED = 0x6;
static const Shortint OPENSSL_SSL_CTRL_GET_SESS_CACHE_MODE = 0x2d;
static const Shortint OPENSSL_SSL_CTRL_GET_SESS_CACHE_SIZE = 0x2b;
static const Shortint OPENSSL_SSL_CTRL_GET_TOTAL_RENEGOTIATIONS = 0xa;
static const Shortint OPENSSL_SSL_CTRL_MODE = 0x21;
static const Shortint OPENSSL_SSL_CTRL_NEED_TMP_RSA = 0x1;
static const Shortint OPENSSL_SSL_CTRL_OPTIONS = 0x20;
static const Shortint OPENSSL_SSL_CTRL_SESS_ACCEPT = 0x18;
static const Shortint OPENSSL_SSL_CTRL_SESS_ACCEPT_GOOD = 0x19;
static const Shortint OPENSSL_SSL_CTRL_SESS_ACCEPT_RENEGOTIATE = 0x1a;
static const Shortint OPENSSL_SSL_CTRL_SESS_CACHE_FULL = 0x1f;
static const Shortint OPENSSL_SSL_CTRL_SESS_CB_HIT = 0x1c;
static const Shortint OPENSSL_SSL_CTRL_SESS_CONNECT = 0x15;
static const Shortint OPENSSL_SSL_CTRL_SESS_CONNECT_GOOD = 0x16;
static const Shortint OPENSSL_SSL_CTRL_SESS_CONNECT_RENEGOTIATE = 0x17;
static const Shortint OPENSSL_SSL_CTRL_SESS_HIT = 0x1b;
static const Shortint OPENSSL_SSL_CTRL_SESS_MISSES = 0x1d;
static const Shortint OPENSSL_SSL_CTRL_SESS_NUMBER = 0x14;
static const Shortint OPENSSL_SSL_CTRL_SESS_TIMEOUTS = 0x1e;
static const Shortint OPENSSL_SSL_CTRL_SET_READ_AHEAD = 0x29;
static const Shortint OPENSSL_SSL_CTRL_SET_SESS_CACHE_MODE = 0x2c;
static const Shortint OPENSSL_SSL_CTRL_SET_SESS_CACHE_SIZE = 0x2a;
static const Shortint OPENSSL_SSL_CTRL_SET_TMP_DH = 0x3;
static const Shortint OPENSSL_SSL_CTRL_SET_TMP_DH_CB = 0x5;
static const Shortint OPENSSL_SSL_CTRL_SET_TMP_RSA = 0x2;
static const Shortint OPENSSL_SSL_CTRL_SET_TMP_RSA_CB = 0x4;
#define OPENSSL_SSL_DEFAULT_CIPHER_LIST "ALL:!ADH:RC4+RSA:+HIGH:+MEDIUM:+LOW:+SSLv2:+EXP"
static const Shortint OPENSSL_SSL_ERROR_NONE = 0x0;
static const Shortint OPENSSL_SSL_ERROR_SSL = 0x1;
static const Shortint OPENSSL_SSL_ERROR_SYSCALL = 0x5;
static const Shortint OPENSSL_SSL_ERROR_WANT_CONNECT = 0x7;
static const Shortint OPENSSL_SSL_ERROR_WANT_READ = 0x2;
static const Shortint OPENSSL_SSL_ERROR_WANT_WRITE = 0x3;
static const Shortint OPENSSL_SSL_ERROR_WANT_X509_LOOKUP = 0x4;
static const Shortint OPENSSL_SSL_ERROR_ZERO_RETURN = 0x6;
static const Shortint OPENSSL_X509_FILETYPE_ASN1 = 0x2;
static const Shortint OPENSSL_SSL_FILETYPE_ASN1 = 0x2;
static const Shortint OPENSSL_X509_FILETYPE_PEM = 0x1;
static const Shortint OPENSSL_SSL_FILETYPE_PEM = 0x1;
static const Shortint OPENSSL_SSL_F_CLIENT_CERTIFICATE = 0x64;
static const Shortint OPENSSL_SSL_F_CLIENT_HELLO = 0x65;
static const Shortint OPENSSL_SSL_F_CLIENT_MASTER_KEY = 0x66;
static const Shortint OPENSSL_SSL_F_D2I_SSL_SESSION = 0x67;
static const Shortint OPENSSL_SSL_F_DO_SSL3_WRITE = 0x68;
static const Shortint OPENSSL_SSL_F_GET_CLIENT_FINISHED = 0x69;
static const Shortint OPENSSL_SSL_F_GET_CLIENT_HELLO = 0x6a;
static const Shortint OPENSSL_SSL_F_GET_CLIENT_MASTER_KEY = 0x6b;
static const Shortint OPENSSL_SSL_F_GET_SERVER_FINISHED = 0x6c;
static const Shortint OPENSSL_SSL_F_GET_SERVER_HELLO = 0x6d;
static const Shortint OPENSSL_SSL_F_GET_SERVER_VERIFY = 0x6e;
static const Shortint OPENSSL_SSL_F_I2D_SSL_SESSION = 0x6f;
static const Shortint OPENSSL_SSL_F_READ_N = 0x70;
static const Shortint OPENSSL_SSL_F_REQUEST_CERTIFICATE = 0x71;
static const Shortint OPENSSL_SSL_F_SERVER_HELLO = 0x72;
static const Shortint OPENSSL_SSL_F_SSL23_ACCEPT = 0x73;
static const Shortint OPENSSL_SSL_F_SSL23_CLIENT_HELLO = 0x74;
static const Shortint OPENSSL_SSL_F_SSL23_CONNECT = 0x75;
static const Shortint OPENSSL_SSL_F_SSL23_GET_CLIENT_HELLO = 0x76;
static const Shortint OPENSSL_SSL_F_SSL23_GET_SERVER_HELLO = 0x77;
static const Shortint OPENSSL_SSL_F_SSL23_READ = 0x78;
static const Shortint OPENSSL_SSL_F_SSL23_WRITE = 0x79;
static const Shortint OPENSSL_SSL_F_SSL2_ACCEPT = 0x7a;
static const Shortint OPENSSL_SSL_F_SSL2_CONNECT = 0x7b;
static const Shortint OPENSSL_SSL_F_SSL2_ENC_INIT = 0x7c;
static const Shortint OPENSSL_SSL_F_SSL2_READ = 0x7d;
static const Shortint OPENSSL_SSL_F_SSL2_SET_CERTIFICATE = 0x7e;
static const Shortint OPENSSL_SSL_F_SSL2_WRITE = 0x7f;
static const System::Byte OPENSSL_SSL_F_SSL3_ACCEPT = 0x80;
static const System::Byte OPENSSL_SSL_F_SSL3_CHANGE_CIPHER_STATE = 0x81;
static const System::Byte OPENSSL_SSL_F_SSL3_CHECK_CERT_AND_ALGORITHM = 0x82;
static const System::Byte OPENSSL_SSL_F_SSL3_CLIENT_HELLO = 0x83;
static const System::Byte OPENSSL_SSL_F_SSL3_CONNECT = 0x84;
static const System::Byte OPENSSL_SSL_F_SSL3_CTRL = 0xd5;
static const System::Byte OPENSSL_SSL_F_SSL3_CTX_CTRL = 0x85;
static const System::Byte OPENSSL_SSL_F_SSL3_ENC = 0x86;
static const System::Byte OPENSSL_SSL_F_SSL3_GET_CERTIFICATE_REQUEST = 0x87;
static const System::Byte OPENSSL_SSL_F_SSL3_GET_CERT_VERIFY = 0x88;
static const System::Byte OPENSSL_SSL_F_SSL3_GET_CLIENT_CERTIFICATE = 0x89;
static const System::Byte OPENSSL_SSL_F_SSL3_GET_CLIENT_HELLO = 0x8a;
static const System::Byte OPENSSL_SSL_F_SSL3_GET_CLIENT_KEY_EXCHANGE = 0x8b;
static const System::Byte OPENSSL_SSL_F_SSL3_GET_FINISHED = 0x8c;
static const System::Byte OPENSSL_SSL_F_SSL3_GET_KEY_EXCHANGE = 0x8d;
static const System::Byte OPENSSL_SSL_F_SSL3_GET_MESSAGE = 0x8e;
static const System::Byte OPENSSL_SSL_F_SSL3_GET_RECORD = 0x8f;
static const System::Byte OPENSSL_SSL_F_SSL3_GET_SERVER_CERTIFICATE = 0x90;
static const System::Byte OPENSSL_SSL_F_SSL3_GET_SERVER_DONE = 0x91;
static const System::Byte OPENSSL_SSL_F_SSL3_GET_SERVER_HELLO = 0x92;
static const System::Byte OPENSSL_SSL_F_SSL3_OUTPUT_CERT_CHAIN = 0x93;
static const System::Byte OPENSSL_SSL_F_SSL3_READ_BYTES = 0x94;
static const System::Byte OPENSSL_SSL_F_SSL3_READ_N = 0x95;
static const System::Byte OPENSSL_SSL_F_SSL3_SEND_CERTIFICATE_REQUEST = 0x96;
static const System::Byte OPENSSL_SSL_F_SSL3_SEND_CLIENT_CERTIFICATE = 0x97;
static const System::Byte OPENSSL_SSL_F_SSL3_SEND_CLIENT_KEY_EXCHANGE = 0x98;
static const System::Byte OPENSSL_SSL_F_SSL3_SEND_CLIENT_VERIFY = 0x99;
static const System::Byte OPENSSL_SSL_F_SSL3_SEND_SERVER_CERTIFICATE = 0x9a;
static const System::Byte OPENSSL_SSL_F_SSL3_SEND_SERVER_KEY_EXCHANGE = 0x9b;
static const System::Byte OPENSSL_SSL_F_SSL3_SETUP_BUFFERS = 0x9c;
static const System::Byte OPENSSL_SSL_F_SSL3_SETUP_KEY_BLOCK = 0x9d;
static const System::Byte OPENSSL_SSL_F_SSL3_WRITE_BYTES = 0x9e;
static const System::Byte OPENSSL_SSL_F_SSL3_WRITE_PENDING = 0x9f;
static const System::Byte OPENSSL_SSL_F_SSL_ADD_DIR_CERT_SUBJECTS_TO_STACK = 0xd7;
static const System::Byte OPENSSL_SSL_F_SSL_ADD_FILE_CERT_SUBJECTS_TO_STACK = 0xd8;
static const System::Byte OPENSSL_SSL_F_SSL_BAD_METHOD = 0xa0;
static const System::Byte OPENSSL_SSL_F_SSL_BYTES_TO_CIPHER_LIST = 0xa1;
static const System::Byte OPENSSL_SSL_F_SSL_CERT_DUP = 0xdd;
static const System::Byte OPENSSL_SSL_F_SSL_CERT_INST = 0xde;
static const System::Byte OPENSSL_SSL_F_SSL_CERT_INSTANTIATE = 0xd6;
static const System::Byte OPENSSL_SSL_F_SSL_CERT_NEW = 0xa2;
static const System::Byte OPENSSL_SSL_F_SSL_CHECK_PRIVATE_KEY = 0xa3;
static const System::Byte OPENSSL_SSL_F_SSL_CLEAR = 0xa4;
static const System::Byte OPENSSL_SSL_F_SSL_COMP_ADD_COMPRESSION_METHOD = 0xa5;
static const System::Byte OPENSSL_SSL_F_SSL_CREATE_CIPHER_LIST = 0xa6;
static const System::Byte OPENSSL_SSL_F_SSL_CTX_CHECK_PRIVATE_KEY = 0xa8;
static const System::Byte OPENSSL_SSL_F_SSL_CTX_NEW = 0xa9;
static const System::Byte OPENSSL_SSL_F_SSL_CTX_SET_SESSION_ID_CONTEXT = 0xdb;
static const System::Byte OPENSSL_SSL_F_SSL_CTX_SET_SSL_VERSION = 0xaa;
static const System::Byte OPENSSL_SSL_F_SSL_CTX_USE_CERTIFICATE = 0xab;
static const System::Byte OPENSSL_SSL_F_SSL_CTX_USE_CERTIFICATE_ASN1 = 0xac;
static const System::Byte OPENSSL_SSL_F_SSL_CTX_USE_CERTIFICATE_CHAIN_FILE = 0xdc;
static const System::Byte OPENSSL_SSL_F_SSL_CTX_USE_CERTIFICATE_FILE = 0xad;
static const System::Byte OPENSSL_SSL_F_SSL_CTX_USE_PRIVATEKEY = 0xae;
static const System::Byte OPENSSL_SSL_F_SSL_CTX_USE_PRIVATEKEY_ASN1 = 0xaf;
static const System::Byte OPENSSL_SSL_F_SSL_CTX_USE_PRIVATEKEY_FILE = 0xb0;
static const System::Byte OPENSSL_SSL_F_SSL_CTX_USE_RSAPRIVATEKEY = 0xb1;
static const System::Byte OPENSSL_SSL_F_SSL_CTX_USE_RSAPRIVATEKEY_ASN1 = 0xb2;
static const System::Byte OPENSSL_SSL_F_SSL_CTX_USE_RSAPRIVATEKEY_FILE = 0xb3;
static const System::Byte OPENSSL_SSL_F_SSL_DO_HANDSHAKE = 0xb4;
static const System::Byte OPENSSL_SSL_F_SSL_GET_NEW_SESSION = 0xb5;
static const System::Byte OPENSSL_SSL_F_SSL_GET_PREV_SESSION = 0xd9;
static const System::Byte OPENSSL_SSL_F_SSL_GET_SERVER_SEND_CERT = 0xb6;
static const System::Byte OPENSSL_SSL_F_SSL_GET_SIGN_PKEY = 0xb7;
static const System::Byte OPENSSL_SSL_F_SSL_INIT_WBIO_BUFFER = 0xb8;
static const System::Byte OPENSSL_SSL_F_SSL_LOAD_CLIENT_CA_FILE = 0xb9;
static const System::Byte OPENSSL_SSL_F_SSL_NEW = 0xba;
static const System::Byte OPENSSL_SSL_F_SSL_READ = 0xdf;
static const System::Byte OPENSSL_SSL_F_SSL_RSA_PRIVATE_DECRYPT = 0xbb;
static const System::Byte OPENSSL_SSL_F_SSL_RSA_PUBLIC_ENCRYPT = 0xbc;
static const System::Byte OPENSSL_SSL_F_SSL_SESSION_NEW = 0xbd;
static const System::Byte OPENSSL_SSL_F_SSL_SESSION_PRINT_FP = 0xbe;
static const System::Byte OPENSSL_SSL_F_SSL_SESS_CERT_NEW = 0xe1;
static const System::Byte OPENSSL_SSL_F_SSL_SET_CERT = 0xbf;
static const System::Byte OPENSSL_SSL_F_SSL_SET_FD = 0xc0;
static const System::Byte OPENSSL_SSL_F_SSL_SET_PKEY = 0xc1;
static const System::Byte OPENSSL_SSL_F_SSL_SET_RFD = 0xc2;
static const System::Byte OPENSSL_SSL_F_SSL_SET_SESSION = 0xc3;
static const System::Byte OPENSSL_SSL_F_SSL_SET_SESSION_ID_CONTEXT = 0xda;
static const System::Byte OPENSSL_SSL_F_SSL_SET_WFD = 0xc4;
static const System::Byte OPENSSL_SSL_F_SSL_SHUTDOWN = 0xe0;
static const System::Byte OPENSSL_SSL_F_SSL_UNDEFINED_FUNCTION = 0xc5;
static const System::Byte OPENSSL_SSL_F_SSL_USE_CERTIFICATE = 0xc6;
static const System::Byte OPENSSL_SSL_F_SSL_USE_CERTIFICATE_ASN1 = 0xc7;
static const System::Byte OPENSSL_SSL_F_SSL_USE_CERTIFICATE_FILE = 0xc8;
static const System::Byte OPENSSL_SSL_F_SSL_USE_PRIVATEKEY = 0xc9;
static const System::Byte OPENSSL_SSL_F_SSL_USE_PRIVATEKEY_ASN1 = 0xca;
static const System::Byte OPENSSL_SSL_F_SSL_USE_PRIVATEKEY_FILE = 0xcb;
static const System::Byte OPENSSL_SSL_F_SSL_USE_RSAPRIVATEKEY = 0xcc;
static const System::Byte OPENSSL_SSL_F_SSL_USE_RSAPRIVATEKEY_ASN1 = 0xcd;
static const System::Byte OPENSSL_SSL_F_SSL_USE_RSAPRIVATEKEY_FILE = 0xce;
static const System::Byte OPENSSL_SSL_F_SSL_VERIFY_CERT_CHAIN = 0xcf;
static const System::Byte OPENSSL_SSL_F_SSL_WRITE = 0xd0;
static const System::Byte OPENSSL_SSL_F_TLS1_CHANGE_CIPHER_STATE = 0xd1;
static const System::Byte OPENSSL_SSL_F_TLS1_ENC = 0xd2;
static const System::Byte OPENSSL_SSL_F_TLS1_SETUP_KEY_BLOCK = 0xd3;
static const System::Byte OPENSSL_SSL_F_WRITE_PENDING = 0xd4;
static const Shortint OPENSSL_SSL_MAX_KEY_ARG_LENGTH = 0x8;
static const Shortint OPENSSL_SSL_MAX_MASTER_KEY_LENGTH = 0x30;
static const Shortint OPENSSL_SSL_MAX_SID_CTX_LENGTH = 0x20;
static const Shortint OPENSSL_SSL_MAX_SSL_SESSION_ID_LENGTH = 0x20;
static const Shortint OPENSSL_SSL_MODE_ACCEPT_MOVING_WRITE_BUFFER = 0x2;
static const Shortint OPENSSL_SSL_MODE_ENABLE_PARTIAL_WRITE = 0x1;
static const Shortint OPENSSL_SSL_NOTHING = 0x1;
static const int OPENSSL_SSL_OP_ALL = 0xfffff;
static const int OPENSSL_SSL_OP_EPHEMERAL_RSA = 0x200000;
static const Shortint OPENSSL_SSL_OP_MICROSOFT_BIG_SSLV3_BUFFER = 0x20;
static const Shortint OPENSSL_SSL_OP_MICROSOFT_SESS_ID_BUG = 0x1;
static const Shortint OPENSSL_SSL_OP_MSIE_SSLV2_RSA_PADDING = 0x40;
static const int OPENSSL_SSL_OP_NETSCAPE_CA_DN_BUG = 0x20000000;
static const Shortint OPENSSL_SSL_OP_NETSCAPE_CHALLENGE_BUG = 0x2;
static const unsigned OPENSSL_SSL_OP_NETSCAPE_DEMO_CIPHER_CHANGE_BUG = 0x80000000;
static const Shortint OPENSSL_SSL_OP_NETSCAPE_REUSE_CIPHER_CHANGE_BUG = 0x8;
static const int OPENSSL_SSL_OP_NON_EXPORT_FIRST = 0x40000000;
static const int OPENSSL_SSL_OP_NO_SSLv2 = 0x1000000;
static const int OPENSSL_SSL_OP_NO_SSLv3 = 0x2000000;
static const int OPENSSL_SSL_OP_NO_TLSv1 = 0x4000000;
static const int OPENSSL_SSL_OP_PKCS1_CHECK_1 = 0x8000000;
static const int OPENSSL_SSL_OP_PKCS1_CHECK_2 = 0x10000000;
static const int OPENSSL_SSL_OP_SINGLE_DH_USE = 0x100000;
static const System::Byte OPENSSL_SSL_OP_SSLEAY_080_CLIENT_DH_BUG = 0x80;
static const Shortint OPENSSL_SSL_OP_SSLREF2_REUSE_CERT_TYPE_BUG = 0x10;
static const Word OPENSSL_SSL_OP_TLS_BLOCK_PADDING_BUG = 0x200;
static const Word OPENSSL_SSL_OP_TLS_D5_BUG = 0x100;
static const Word OPENSSL_SSL_OP_TLS_ROLLBACK_BUG = 0x400;
static const Shortint OPENSSL_SSL_READING = 0x3;
static const Shortint OPENSSL_SSL_RECEIVED_SHUTDOWN = 0x2;
static const Shortint OPENSSL_SSL_R_APP_DATA_IN_HANDSHAKE = 0x64;
static const Word OPENSSL_SSL_R_ATTEMPT_TO_REUSE_SESSION_IN_DIFFERENT_CONTEXT = 0x110;
static const Shortint OPENSSL_SSL_R_BAD_ALERT_RECORD = 0x65;
static const Shortint OPENSSL_SSL_R_BAD_AUTHENTICATION_TYPE = 0x66;
static const Shortint OPENSSL_SSL_R_BAD_CHANGE_CIPHER_SPEC = 0x67;
static const Shortint OPENSSL_SSL_R_BAD_CHECKSUM = 0x68;
static const Shortint OPENSSL_SSL_R_BAD_CLIENT_REQUEST = 0x69;
static const Shortint OPENSSL_SSL_R_BAD_DATA_RETURNED_BY_CALLBACK = 0x6a;
static const Shortint OPENSSL_SSL_R_BAD_DECOMPRESSION = 0x6b;
static const Shortint OPENSSL_SSL_R_BAD_DH_G_LENGTH = 0x6c;
static const Shortint OPENSSL_SSL_R_BAD_DH_PUB_KEY_LENGTH = 0x6d;
static const Shortint OPENSSL_SSL_R_BAD_DH_P_LENGTH = 0x6e;
static const Shortint OPENSSL_SSL_R_BAD_DIGEST_LENGTH = 0x6f;
static const Shortint OPENSSL_SSL_R_BAD_DSA_SIGNATURE = 0x70;
static const Word OPENSSL_SSL_R_BAD_LENGTH = 0x10f;
static const Shortint OPENSSL_SSL_R_BAD_MAC_DECODE = 0x71;
static const Shortint OPENSSL_SSL_R_BAD_MESSAGE_TYPE = 0x72;
static const Shortint OPENSSL_SSL_R_BAD_PACKET_LENGTH = 0x73;
static const Shortint OPENSSL_SSL_R_BAD_PROTOCOL_VERSION_NUMBER = 0x74;
static const Shortint OPENSSL_SSL_R_BAD_RESPONSE_ARGUMENT = 0x75;
static const Shortint OPENSSL_SSL_R_BAD_RSA_DECRYPT = 0x76;
static const Shortint OPENSSL_SSL_R_BAD_RSA_ENCRYPT = 0x77;
static const Shortint OPENSSL_SSL_R_BAD_RSA_E_LENGTH = 0x78;
static const Shortint OPENSSL_SSL_R_BAD_RSA_MODULUS_LENGTH = 0x79;
static const Shortint OPENSSL_SSL_R_BAD_RSA_SIGNATURE = 0x7a;
static const Shortint OPENSSL_SSL_R_BAD_SIGNATURE = 0x7b;
static const Shortint OPENSSL_SSL_R_BAD_SSL_FILETYPE = 0x7c;
static const Shortint OPENSSL_SSL_R_BAD_SSL_SESSION_ID_LENGTH = 0x7d;
static const Shortint OPENSSL_SSL_R_BAD_STATE = 0x7e;
static const Shortint OPENSSL_SSL_R_BAD_WRITE_RETRY = 0x7f;
static const System::Byte OPENSSL_SSL_R_BIO_NOT_SET = 0x80;
static const System::Byte OPENSSL_SSL_R_BLOCK_CIPHER_PAD_IS_WRONG = 0x81;
static const System::Byte OPENSSL_SSL_R_BN_LIB = 0x82;
static const System::Byte OPENSSL_SSL_R_CA_DN_LENGTH_MISMATCH = 0x83;
static const System::Byte OPENSSL_SSL_R_CA_DN_TOO_LONG = 0x84;
static const System::Byte OPENSSL_SSL_R_CCS_RECEIVED_EARLY = 0x85;
static const System::Byte OPENSSL_SSL_R_CERTIFICATE_VERIFY_FAILED = 0x86;
static const System::Byte OPENSSL_SSL_R_CERT_LENGTH_MISMATCH = 0x87;
static const System::Byte OPENSSL_SSL_R_CHALLENGE_IS_DIFFERENT = 0x88;
static const System::Byte OPENSSL_SSL_R_CIPHER_CODE_WRONG_LENGTH = 0x89;
static const System::Byte OPENSSL_SSL_R_CIPHER_OR_HASH_UNAVAILABLE = 0x8a;
static const System::Byte OPENSSL_SSL_R_CIPHER_TABLE_SRC_ERROR = 0x8b;
static const System::Byte OPENSSL_SSL_R_COMPRESSED_LENGTH_TOO_LONG = 0x8c;
static const System::Byte OPENSSL_SSL_R_COMPRESSION_FAILURE = 0x8d;
static const System::Byte OPENSSL_SSL_R_COMPRESSION_LIBRARY_ERROR = 0x8e;
static const System::Byte OPENSSL_SSL_R_CONNECTION_ID_IS_DIFFERENT = 0x8f;
static const System::Byte OPENSSL_SSL_R_CONNECTION_TYPE_NOT_SET = 0x90;
static const System::Byte OPENSSL_SSL_R_DATA_BETWEEN_CCS_AND_FINISHED = 0x91;
static const System::Byte OPENSSL_SSL_R_DATA_LENGTH_TOO_LONG = 0x92;
static const System::Byte OPENSSL_SSL_R_DECRYPTION_FAILED = 0x93;
static const System::Byte OPENSSL_SSL_R_DH_PUBLIC_VALUE_LENGTH_IS_WRONG = 0x94;
static const System::Byte OPENSSL_SSL_R_DIGEST_CHECK_FAILED = 0x95;
static const System::Byte OPENSSL_SSL_R_ENCRYPTED_LENGTH_TOO_LONG = 0x96;
static const System::Byte OPENSSL_SSL_R_ERROR_IN_RECEIVED_CIPHER_LIST = 0x97;
static const System::Byte OPENSSL_SSL_R_EXCESSIVE_MESSAGE_SIZE = 0x98;
static const System::Byte OPENSSL_SSL_R_EXTRA_DATA_IN_MESSAGE = 0x99;
static const System::Byte OPENSSL_SSL_R_GOT_A_FIN_BEFORE_A_CCS = 0x9a;
static const System::Byte OPENSSL_SSL_R_HTTPS_PROXY_REQUEST = 0x9b;
static const System::Byte OPENSSL_SSL_R_HTTP_REQUEST = 0x9c;
static const System::Byte OPENSSL_SSL_R_INTERNAL_ERROR = 0x9d;
static const System::Byte OPENSSL_SSL_R_INVALID_CHALLENGE_LENGTH = 0x9e;
static const System::Byte OPENSSL_SSL_R_LENGTH_MISMATCH = 0x9f;
static const System::Byte OPENSSL_SSL_R_LENGTH_TOO_SHORT = 0xa0;
static const Word OPENSSL_SSL_R_LIBRARY_BUG = 0x112;
static const System::Byte OPENSSL_SSL_R_LIBRARY_HAS_NO_CIPHERS = 0xa1;
static const System::Byte OPENSSL_SSL_R_MISSING_DH_DSA_CERT = 0xa2;
static const System::Byte OPENSSL_SSL_R_MISSING_DH_KEY = 0xa3;
static const System::Byte OPENSSL_SSL_R_MISSING_DH_RSA_CERT = 0xa4;
static const System::Byte OPENSSL_SSL_R_MISSING_DSA_SIGNING_CERT = 0xa5;
static const System::Byte OPENSSL_SSL_R_MISSING_EXPORT_TMP_DH_KEY = 0xa6;
static const System::Byte OPENSSL_SSL_R_MISSING_EXPORT_TMP_RSA_KEY = 0xa7;
static const System::Byte OPENSSL_SSL_R_MISSING_RSA_CERTIFICATE = 0xa8;
static const System::Byte OPENSSL_SSL_R_MISSING_RSA_ENCRYPTING_CERT = 0xa9;
static const System::Byte OPENSSL_SSL_R_MISSING_RSA_SIGNING_CERT = 0xaa;
static const System::Byte OPENSSL_SSL_R_MISSING_TMP_DH_KEY = 0xab;
static const System::Byte OPENSSL_SSL_R_MISSING_TMP_RSA_KEY = 0xac;
static const System::Byte OPENSSL_SSL_R_MISSING_TMP_RSA_PKEY = 0xad;
static const System::Byte OPENSSL_SSL_R_MISSING_VERIFY_MESSAGE = 0xae;
static const System::Byte OPENSSL_SSL_R_NON_SSLV2_INITIAL_PACKET = 0xaf;
static const System::Byte OPENSSL_SSL_R_NO_CERTIFICATES_RETURNED = 0xb0;
static const System::Byte OPENSSL_SSL_R_NO_CERTIFICATE_ASSIGNED = 0xb1;
static const System::Byte OPENSSL_SSL_R_NO_CERTIFICATE_RETURNED = 0xb2;
static const System::Byte OPENSSL_SSL_R_NO_CERTIFICATE_SET = 0xb3;
static const System::Byte OPENSSL_SSL_R_NO_CERTIFICATE_SPECIFIED = 0xb4;
static const System::Byte OPENSSL_SSL_R_NO_CIPHERS_AVAILABLE = 0xb5;
static const System::Byte OPENSSL_SSL_R_NO_CIPHERS_PASSED = 0xb6;
static const System::Byte OPENSSL_SSL_R_NO_CIPHERS_SPECIFIED = 0xb7;
static const System::Byte OPENSSL_SSL_R_NO_CIPHER_LIST = 0xb8;
static const System::Byte OPENSSL_SSL_R_NO_CIPHER_MATCH = 0xb9;
static const System::Byte OPENSSL_SSL_R_NO_CLIENT_CERT_RECEIVED = 0xba;
static const System::Byte OPENSSL_SSL_R_NO_COMPRESSION_SPECIFIED = 0xbb;
static const System::Byte OPENSSL_SSL_R_NO_METHOD_SPECIFIED = 0xbc;
static const System::Byte OPENSSL_SSL_R_NO_PRIVATEKEY = 0xbd;
static const System::Byte OPENSSL_SSL_R_NO_PRIVATE_KEY_ASSIGNED = 0xbe;
static const System::Byte OPENSSL_SSL_R_NO_PROTOCOLS_AVAILABLE = 0xbf;
static const System::Byte OPENSSL_SSL_R_NO_PUBLICKEY = 0xc0;
static const System::Byte OPENSSL_SSL_R_NO_SHARED_CIPHER = 0xc1;
static const System::Byte OPENSSL_SSL_R_NO_VERIFY_CALLBACK = 0xc2;
static const System::Byte OPENSSL_SSL_R_NULL_SSL_CTX = 0xc3;
static const System::Byte OPENSSL_SSL_R_NULL_SSL_METHOD_PASSED = 0xc4;
static const System::Byte OPENSSL_SSL_R_OLD_SESSION_CIPHER_NOT_RETURNED = 0xc5;
static const System::Byte OPENSSL_SSL_R_PACKET_LENGTH_TOO_LONG = 0xc6;
static const Word OPENSSL_SSL_R_PATH_TOO_LONG = 0x10e;
static const System::Byte OPENSSL_SSL_R_PEER_DID_NOT_RETURN_A_CERTIFICATE = 0xc7;
static const System::Byte OPENSSL_SSL_R_PEER_ERROR = 0xc8;
static const System::Byte OPENSSL_SSL_R_PEER_ERROR_CERTIFICATE = 0xc9;
static const System::Byte OPENSSL_SSL_R_PEER_ERROR_NO_CERTIFICATE = 0xca;
static const System::Byte OPENSSL_SSL_R_PEER_ERROR_NO_CIPHER = 0xcb;
static const System::Byte OPENSSL_SSL_R_PEER_ERROR_UNSUPPORTED_CERTIFICATE_TYPE = 0xcc;
static const System::Byte OPENSSL_SSL_R_PRE_MAC_LENGTH_TOO_LONG = 0xcd;
static const System::Byte OPENSSL_SSL_R_PROBLEMS_MAPPING_CIPHER_FUNCTIONS = 0xce;
static const System::Byte OPENSSL_SSL_R_PROTOCOL_IS_SHUTDOWN = 0xcf;
static const System::Byte OPENSSL_SSL_R_PUBLIC_KEY_ENCRYPT_ERROR = 0xd0;
static const System::Byte OPENSSL_SSL_R_PUBLIC_KEY_IS_NOT_RSA = 0xd1;
static const System::Byte OPENSSL_SSL_R_PUBLIC_KEY_NOT_RSA = 0xd2;
static const System::Byte OPENSSL_SSL_R_READ_BIO_NOT_SET = 0xd3;
static const System::Byte OPENSSL_SSL_R_READ_WRONG_PACKET_TYPE = 0xd4;
static const System::Byte OPENSSL_SSL_R_RECORD_LENGTH_MISMATCH = 0xd5;
static const System::Byte OPENSSL_SSL_R_RECORD_TOO_LARGE = 0xd6;
static const System::Byte OPENSSL_SSL_R_REQUIRED_CIPHER_MISSING = 0xd7;
static const System::Byte OPENSSL_SSL_R_REUSE_CERT_LENGTH_NOT_ZERO = 0xd8;
static const System::Byte OPENSSL_SSL_R_REUSE_CERT_TYPE_NOT_ZERO = 0xd9;
static const System::Byte OPENSSL_SSL_R_REUSE_CIPHER_LIST_NOT_ZERO = 0xda;
static const Word OPENSSL_SSL_R_SESSION_ID_CONTEXT_UNINITIALIZED = 0x115;
static const System::Byte OPENSSL_SSL_R_SHORT_READ = 0xdb;
static const System::Byte OPENSSL_SSL_R_SIGNATURE_FOR_NON_SIGNING_CERTIFICATE = 0xdc;
static const System::Byte OPENSSL_SSL_R_SSL23_DOING_SESSION_ID_REUSE = 0xdd;
static const System::Byte OPENSSL_SSL_R_SSL3_SESSION_ID_TOO_SHORT = 0xde;
static const Word OPENSSL_SSL_R_SSLV3_ALERT_BAD_CERTIFICATE = 0x412;
static const Word OPENSSL_SSL_R_SSLV3_ALERT_BAD_RECORD_MAC = 0x3fc;
static const Word OPENSSL_SSL_R_SSLV3_ALERT_CERTIFICATE_EXPIRED = 0x415;
static const Word OPENSSL_SSL_R_SSLV3_ALERT_CERTIFICATE_REVOKED = 0x414;
static const Word OPENSSL_SSL_R_SSLV3_ALERT_CERTIFICATE_UNKNOWN = 0x416;
static const Word OPENSSL_SSL_R_SSLV3_ALERT_DECOMPRESSION_FAILURE = 0x406;
static const Word OPENSSL_SSL_R_SSLV3_ALERT_HANDSHAKE_FAILURE = 0x410;
static const Word OPENSSL_SSL_R_SSLV3_ALERT_ILLEGAL_PARAMETER = 0x417;
static const Word OPENSSL_SSL_R_SSLV3_ALERT_NO_CERTIFICATE = 0x411;
static const System::Byte OPENSSL_SSL_R_SSLV3_ALERT_PEER_ERROR_CERTIFICATE = 0xdf;
static const System::Byte OPENSSL_SSL_R_SSLV3_ALERT_PEER_ERROR_NO_CERTIFICATE = 0xe0;
static const System::Byte OPENSSL_SSL_R_SSLV3_ALERT_PEER_ERROR_NO_CIPHER = 0xe1;
static const System::Byte OPENSSL_SSL_R_SSLV3_ALERT_PEER_ERROR_UNSUPPORTED_CERTIFICATE_TYPE = 0xe2;
static const Word OPENSSL_SSL_R_SSLV3_ALERT_UNEXPECTED_MESSAGE = 0x3f2;
static const System::Byte OPENSSL_SSL_R_SSLV3_ALERT_UNKNOWN_REMOTE_ERROR_TYPE = 0xe3;
static const Word OPENSSL_SSL_R_SSLV3_ALERT_UNSUPPORTED_CERTIFICATE = 0x413;
static const System::Byte OPENSSL_SSL_R_SSL_CTX_HAS_NO_DEFAULT_SSL_VERSION = 0xe4;
static const System::Byte OPENSSL_SSL_R_SSL_HANDSHAKE_FAILURE = 0xe5;
static const System::Byte OPENSSL_SSL_R_SSL_LIBRARY_HAS_NO_CIPHERS = 0xe6;
static const Word OPENSSL_SSL_R_SSL_SESSION_ID_CONTEXT_TOO_LONG = 0x111;
static const System::Byte OPENSSL_SSL_R_SSL_SESSION_ID_IS_DIFFERENT = 0xe7;
static const Word OPENSSL_SSL_R_TLSV1_ALERT_ACCESS_DENIED = 0x419;
static const Word OPENSSL_SSL_R_TLSV1_ALERT_DECODE_ERROR = 0x41a;
static const Word OPENSSL_SSL_R_TLSV1_ALERT_DECRYPTION_FAILED = 0x3fd;
static const Word OPENSSL_SSL_R_TLSV1_ALERT_DECRYPT_ERROR = 0x41b;
static const Word OPENSSL_SSL_R_TLSV1_ALERT_EXPORT_RESTRICION = 0x424;
static const Word OPENSSL_SSL_R_TLSV1_ALERT_INSUFFICIENT_SECURITY = 0x42f;
static const Word OPENSSL_SSL_R_TLSV1_ALERT_INTERNAL_ERROR = 0x438;
static const Word OPENSSL_SSL_R_TLSV1_ALERT_NO_RENEGOTIATION = 0x44c;
static const Word OPENSSL_SSL_R_TLSV1_ALERT_PROTOCOL_VERSION = 0x42e;
static const Word OPENSSL_SSL_R_TLSV1_ALERT_RECORD_OVERFLOW = 0x3fe;
static const Word OPENSSL_SSL_R_TLSV1_ALERT_UNKNOWN_CA = 0x418;
static const Word OPENSSL_SSL_R_TLSV1_ALERT_USER_CANCLED = 0x442;
static const System::Byte OPENSSL_SSL_R_TLS_CLIENT_CERT_REQ_WITH_ANON_CIPHER = 0xe8;
static const System::Byte OPENSSL_SSL_R_TLS_PEER_DID_NOT_RESPOND_WITH_CERTIFICATE_LIST = 0xe9;
static const System::Byte OPENSSL_SSL_R_TLS_RSA_ENCRYPTED_VALUE_LENGTH_IS_WRONG = 0xea;
static const System::Byte OPENSSL_SSL_R_TRIED_TO_USE_UNSUPPORTED_CIPHER = 0xeb;
static const System::Byte OPENSSL_SSL_R_UNABLE_TO_DECODE_DH_CERTS = 0xec;
static const System::Byte OPENSSL_SSL_R_UNABLE_TO_EXTRACT_PUBLIC_KEY = 0xed;
static const System::Byte OPENSSL_SSL_R_UNABLE_TO_FIND_DH_PARAMETERS = 0xee;
static const System::Byte OPENSSL_SSL_R_UNABLE_TO_FIND_PUBLIC_KEY_PARAMETERS = 0xef;
static const System::Byte OPENSSL_SSL_R_UNABLE_TO_FIND_SSL_METHOD = 0xf0;
static const System::Byte OPENSSL_SSL_R_UNABLE_TO_LOAD_SSL2_MD5_ROUTINES = 0xf1;
static const System::Byte OPENSSL_SSL_R_UNABLE_TO_LOAD_SSL3_MD5_ROUTINES = 0xf2;
static const System::Byte OPENSSL_SSL_R_UNABLE_TO_LOAD_SSL3_SHA1_ROUTINES = 0xf3;
static const System::Byte OPENSSL_SSL_R_UNEXPECTED_MESSAGE = 0xf4;
static const System::Byte OPENSSL_SSL_R_UNEXPECTED_RECORD = 0xf5;
static const Word OPENSSL_SSL_R_UNINITIALIZED = 0x114;
static const System::Byte OPENSSL_SSL_R_UNKNOWN_ALERT_TYPE = 0xf6;
static const System::Byte OPENSSL_SSL_R_UNKNOWN_CERTIFICATE_TYPE = 0xf7;
static const System::Byte OPENSSL_SSL_R_UNKNOWN_CIPHER_RETURNED = 0xf8;
static const System::Byte OPENSSL_SSL_R_UNKNOWN_CIPHER_TYPE = 0xf9;
static const System::Byte OPENSSL_SSL_R_UNKNOWN_KEY_EXCHANGE_TYPE = 0xfa;
static const System::Byte OPENSSL_SSL_R_UNKNOWN_PKEY_TYPE = 0xfb;
static const System::Byte OPENSSL_SSL_R_UNKNOWN_PROTOCOL = 0xfc;
static const System::Byte OPENSSL_SSL_R_UNKNOWN_REMOTE_ERROR_TYPE = 0xfd;
static const System::Byte OPENSSL_SSL_R_UNKNOWN_SSL_VERSION = 0xfe;
static const System::Byte OPENSSL_SSL_R_UNKNOWN_STATE = 0xff;
static const Word OPENSSL_SSL_R_UNSUPPORTED_CIPHER = 0x100;
static const Word OPENSSL_SSL_R_UNSUPPORTED_COMPRESSION_ALGORITHM = 0x101;
static const Word OPENSSL_SSL_R_UNSUPPORTED_PROTOCOL = 0x102;
static const Word OPENSSL_SSL_R_UNSUPPORTED_SSL_VERSION = 0x103;
static const Word OPENSSL_SSL_R_WRITE_BIO_NOT_SET = 0x104;
static const Word OPENSSL_SSL_R_WRONG_CIPHER_RETURNED = 0x105;
static const Word OPENSSL_SSL_R_WRONG_MESSAGE_TYPE = 0x106;
static const Word OPENSSL_SSL_R_WRONG_NUMBER_OF_KEY_BITS = 0x107;
static const Word OPENSSL_SSL_R_WRONG_SIGNATURE_LENGTH = 0x108;
static const Word OPENSSL_SSL_R_WRONG_SIGNATURE_SIZE = 0x109;
static const Word OPENSSL_SSL_R_WRONG_SSL_VERSION = 0x10a;
static const Word OPENSSL_SSL_R_WRONG_VERSION_NUMBER = 0x10b;
static const Word OPENSSL_SSL_R_X509_LIB = 0x10c;
static const Word OPENSSL_SSL_R_X509_VERIFICATION_SETUP_PROBLEMS = 0x10d;
static const Shortint OPENSSL_SSL_SENT_SHUTDOWN = 0x1;
static const Shortint OPENSSL_SSL_SESSION_ASN1_VERSION = 0x1;
static const Word OPENSSL_SSL_SESSION_CACHE_MAX_SIZE_DEFAULT = 0x5000;
static const Shortint OPENSSL_SSL_SESS_CACHE_CLIENT = 0x1;
static const Shortint OPENSSL_SSL_SESS_CACHE_SERVER = 0x2;
static const Shortint OPENSSL_SSL_SESS_CACHE_BOTH = 0x3;
static const System::Byte OPENSSL_SSL_SESS_CACHE_NO_AUTO_CLEAR = 0x80;
static const Word OPENSSL_SSL_SESS_CACHE_NO_INTERNAL_LOOKUP = 0x100;
static const Shortint OPENSSL_SSL_SESS_CACHE_OFF = 0x0;
static const Word OPENSSL_SSL_ST_BEFORE = 0x4000;
static const Word OPENSSL_SSL_ST_INIT = 0x3000;
static const Word OPENSSL_SSL_ST_MASK = 0xfff;
static const Shortint OPENSSL_SSL_ST_OK = 0x3;
static const System::Byte OPENSSL_SSL_ST_READ_BODY = 0xf1;
static const System::Byte OPENSSL_SSL_ST_READ_DONE = 0xf2;
static const System::Byte OPENSSL_SSL_ST_READ_HEADER = 0xf0;
static const Word OPENSSL_SSL_ST_RENEGOTIATE = 0x3004;
#define OPENSSL_SSL_TXT_3DES "3DES"
#define OPENSSL_SSL_TXT_ADH_C "ADH"
#define OPENSSL_SSL_TXT_ALL "ALL"
#define OPENSSL_SSL_TXT_DES "DES"
#define OPENSSL_SSL_TXT_DES_192_EDE3_CBC_WITH_MD5 "DES-CBC3-MD5"
#define OPENSSL_SSL_TXT_DES_192_EDE3_CBC_WITH_SHA "DES-CBC3-SHA"
#define OPENSSL_SSL_TXT_DES_64_CBC_WITH_MD5 "DES-CBC-MD5"
#define OPENSSL_SSL_TXT_DES_64_CBC_WITH_SHA "DES-CBC-SHA"
#define OPENSSL_SSL_TXT_DH "DH"
#define OPENSSL_SSL_TXT_DSS "DSS"
#define OPENSSL_SSL_TXT_EDH "EDH"
#define OPENSSL_SSL_TXT_EXP40 "EXP"
#define OPENSSL_SSL_TXT_EXP56 "EXPORT56"
#define OPENSSL_SSL_TXT_EXPORT "EXPORT"
#define OPENSSL_SSL_TXT_FZA "FZA"
#define OPENSSL_SSL_TXT_HIGH "HIGH"
#define OPENSSL_SSL_TXT_IDEA "IDEA"
#define OPENSSL_SSL_TXT_IDEA_128_CBC_WITH_MD5 "IDEA-CBC-MD5"
#define OPENSSL_SSL_TXT_LOW "LOW"
#define OPENSSL_SSL_TXT_MD5 "MD5"
#define OPENSSL_SSL_TXT_MEDIUM "MEDIUM"
#define OPENSSL_SSL_TXT_NULL "NULL"
#define OPENSSL_SSL_TXT_NULL_WITH_MD5 "NULL-MD5"
#define OPENSSL_SSL_TXT_RC2 "RC2"
#define OPENSSL_SSL_TXT_RC2_128_CBC_EXPORT40_WITH_MD5 "EXP-RC2-CBC-MD5"
#define OPENSSL_SSL_TXT_RC2_128_CBC_WITH_MD5 "RC2-CBC-MD5"
#define OPENSSL_SSL_TXT_RC4 "RC4"
#define OPENSSL_SSL_TXT_RC4_128_EXPORT40_WITH_MD5 "EXP-RC4-MD5"
#define OPENSSL_SSL_TXT_RC4_128_WITH_MD5 "RC4-MD5"
#define OPENSSL_SSL_TXT_RSA "RSA"
#define OPENSSL_SSL_TXT_SHA "SHA"
#define OPENSSL_SSL_TXT_SHA1 "SHA1"
#define OPENSSL_SSL_TXT_SSLV2 "SSLv2"
#define OPENSSL_SSL_TXT_SSLV3 "SSLv3"
#define OPENSSL_SSL_TXT_TLSV1 "TLSv1"
#define OPENSSL_SSL_TXT_aDH_S "aDH"
#define OPENSSL_SSL_TXT_aDSS "aDSS"
#define OPENSSL_SSL_TXT_aFZA "aFZA"
#define OPENSSL_SSL_TXT_aNULL "aNULL"
#define OPENSSL_SSL_TXT_aRSA "aRSA"
#define OPENSSL_SSL_TXT_eFZA "eFZA"
#define OPENSSL_SSL_TXT_eNULL "eNULL"
#define OPENSSL_SSL_TXT_kDHd "kDHd"
#define OPENSSL_SSL_TXT_kDHr "kDHr"
#define OPENSSL_SSL_TXT_kEDH "kEDH"
#define OPENSSL_SSL_TXT_kFZA "kFZA"
#define OPENSSL_SSL_TXT_kRSA "kRSA"
static const Shortint OPENSSL_SSL_VERIFY_CLIENT_ONCE = 0x4;
static const Shortint OPENSSL_SSL_VERIFY_FAIL_IF_NO_PEER_CERT = 0x2;
static const Shortint OPENSSL_SSL_VERIFY_NONE = 0x0;
static const Shortint OPENSSL_SSL_VERIFY_PEER = 0x1;
static const Shortint OPENSSL_SSL_WRITING = 0x2;
static const Shortint OPENSSL_SSL_X509_LOOKUP = 0x4;
static const Shortint OPENSSL_TLS1_ALLOW_EXPERIMENTAL_CIPHERSUITES = 0x0;
static const int OPENSSL_TLS1_CK_DHE_DSS_EXPORT1024_WITH_DES_CBC_SHA = 0x3000063;
static const int OPENSSL_TLS1_CK_DHE_DSS_EXPORT1024_WITH_RC4_56_SHA = 0x3000065;
static const int OPENSSL_TLS1_CK_DHE_DSS_WITH_RC4_128_SHA = 0x3000066;
static const int OPENSSL_TLS1_CK_RSA_EXPORT1024_WITH_DES_CBC_SHA = 0x3000062;
static const int OPENSSL_TLS1_CK_RSA_EXPORT1024_WITH_RC2_CBC_56_MD5 = 0x3000061;
static const int OPENSSL_TLS1_CK_RSA_EXPORT1024_WITH_RC4_56_MD5 = 0x3000060;
static const int OPENSSL_TLS1_CK_RSA_EXPORT1024_WITH_RC4_56_SHA = 0x3000064;
static const Shortint OPENSSL_TLS1_FINISH_MAC_LENGTH = 0xc;
static const Shortint OPENSSL_TLS1_FLAGS_TLS_PADDING_BUG = 0x8;
#define OPENSSL_TLS1_TXT_DHE_DSS_EXPORT1024_WITH_DES_CBC_SHA "EXP1024-DHE-DSS-DES-CBC-SHA"
#define OPENSSL_TLS1_TXT_DHE_DSS_EXPORT1024_WITH_RC4_56_SHA "EXP1024-DHE-DSS-RC4-SHA"
#define OPENSSL_TLS1_TXT_DHE_DSS_WITH_RC4_128_SHA "DHE-DSS-RC4-SHA"
#define OPENSSL_TLS1_TXT_RSA_EXPORT1024_WITH_DES_CBC_SHA "EXP1024-DES-CBC-SHA"
#define OPENSSL_TLS1_TXT_RSA_EXPORT1024_WITH_RC2_CBC_56_MD5 "EXP1024-RC2-CBC-MD5"
#define OPENSSL_TLS1_TXT_RSA_EXPORT1024_WITH_RC4_56_MD5 "EXP1024-RC4-MD5"
#define OPENSSL_TLS1_TXT_RSA_EXPORT1024_WITH_RC4_56_SHA "EXP1024-RC4-SHA"
static const Word OPENSSL_TLS1_VERSION = 0x301;
static const Shortint OPENSSL_TLS1_VERSION_MAJOR = 0x3;
static const Shortint OPENSSL_TLS1_VERSION_MINOR = 0x1;
static const Shortint OPENSSL_TLS_CT_DSS_FIXED_DH = 0x4;
static const Shortint OPENSSL_TLS_CT_DSS_SIGN = 0x2;
static const Shortint OPENSSL_TLS_CT_NUMBER = 0x4;
static const Shortint OPENSSL_TLS_CT_RSA_FIXED_DH = 0x3;
static const Shortint OPENSSL_TLS_CT_RSA_SIGN = 0x1;
#define OPENSSL_TLS_MD_CLIENT_FINISH_CONST "client finished"
static const Shortint OPENSSL_TLS_MD_CLIENT_FINISH_CONST_SIZE = 0xf;
#define OPENSSL_TLS_MD_CLIENT_WRITE_KEY_CONST "client write key"
static const Shortint OPENSSL_TLS_MD_CLIENT_WRITE_KEY_CONST_SIZE = 0x10;
#define OPENSSL_TLS_MD_IV_BLOCK_CONST "IV block"
static const Shortint OPENSSL_TLS_MD_IV_BLOCK_CONST_SIZE = 0x8;
#define OPENSSL_TLS_MD_KEY_EXPANSION_CONST "key expansion"
static const Shortint OPENSSL_TLS_MD_KEY_EXPANSION_CONST_SIZE = 0xd;
#define OPENSSL_TLS_MD_MASTER_SECRET_CONST "master secret"
static const Shortint OPENSSL_TLS_MD_MASTER_SECRET_CONST_SIZE = 0xd;
static const Shortint OPENSSL_TLS_MD_MAX_CONST_SIZE = 0x14;
#define OPENSSL_TLS_MD_SERVER_FINISH_CONST "server finished"
static const Shortint OPENSSL_TLS_MD_SERVER_FINISH_CONST_SIZE = 0xf;
#define OPENSSL_TLS_MD_SERVER_WRITE_KEY_CONST "server write key"
static const Shortint OPENSSL_TLS_MD_SERVER_WRITE_KEY_CONST_SIZE = 0x10;
static const Shortint OPENSSL_TMP_MAX = 0x1a;
static const Shortint OPENSSL_V_ASN1_APPLICATION = 0x40;
static const Shortint OPENSSL_V_ASN1_APP_CHOOSE = 0xfffffffe;
static const Shortint OPENSSL_V_ASN1_BIT_STRING = 0x3;
static const Shortint OPENSSL_V_ASN1_BMPSTRING = 0x1e;
static const Shortint OPENSSL_V_ASN1_BOOLEAN = 0x1;
static const Shortint OPENSSL_V_ASN1_CONSTRUCTED = 0x20;
static const System::Byte OPENSSL_V_ASN1_CONTEXT_SPECIFIC = 0x80;
static const Shortint OPENSSL_V_ASN1_ENUMERATED = 0xa;
static const Shortint OPENSSL_V_ASN1_EOC = 0x0;
static const Shortint OPENSSL_V_ASN1_EXTERNAL = 0x8;
static const Shortint OPENSSL_V_ASN1_GENERALIZEDTIME = 0x18;
static const Shortint OPENSSL_V_ASN1_GENERALSTRING = 0x1b;
static const Shortint OPENSSL_V_ASN1_GRAPHICSTRING = 0x19;
static const Shortint OPENSSL_V_ASN1_IA5STRING = 0x16;
static const Shortint OPENSSL_V_ASN1_INTEGER = 0x2;
static const Shortint OPENSSL_V_ASN1_ISO64STRING = 0x1a;
static const Word OPENSSL_V_ASN1_NEG_ENUMERATED = 0x10a;
static const Word OPENSSL_V_ASN1_NEG_INTEGER = 0x102;
static const Shortint OPENSSL_V_ASN1_NULL = 0x5;
static const Shortint OPENSSL_V_ASN1_NUMERICSTRING = 0x12;
static const Shortint OPENSSL_V_ASN1_OBJECT = 0x6;
static const Shortint OPENSSL_V_ASN1_OBJECT_DESCRIPTOR = 0x7;
static const Shortint OPENSSL_V_ASN1_OCTET_STRING = 0x4;
static const Shortint OPENSSL_V_ASN1_PRIMATIVE_TAG = 0x1f;
static const Shortint OPENSSL_V_ASN1_PRIMITIVE_TAG = 0x1f;
static const Shortint OPENSSL_V_ASN1_PRINTABLESTRING = 0x13;
static const System::Byte OPENSSL_V_ASN1_PRIVATE = 0xc0;
static const Shortint OPENSSL_V_ASN1_REAL = 0x9;
static const Shortint OPENSSL_V_ASN1_SEQUENCE = 0x10;
static const Shortint OPENSSL_V_ASN1_SET = 0x11;
static const Shortint OPENSSL_V_ASN1_T61STRING = 0x14;
static const Shortint OPENSSL_V_ASN1_TELETEXSTRING = 0x14;
static const Shortint OPENSSL_V_ASN1_UNDEF = 0xffffffff;
static const Shortint OPENSSL_V_ASN1_UNIVERSAL = 0x0;
static const Shortint OPENSSL_V_ASN1_UNIVERSALSTRING = 0x1c;
static const Shortint OPENSSL_V_ASN1_UTCTIME = 0x17;
static const Shortint OPENSSL_V_ASN1_UTF8STRING = 0xc;
static const Shortint OPENSSL_V_ASN1_VIDEOTEXSTRING = 0x15;
static const Shortint OPENSSL_V_ASN1_VISIBLESTRING = 0x1a;
static const Shortint OPENSSL_WINNT = 0x1;
static const Shortint OPENSSL_X509_EXT_PACK_STRING = 0x2;
static const Shortint OPENSSL_X509_EXT_PACK_UNKNOWN = 0x1;
static const Shortint OPENSSL_X509_EX_V_INIT = 0x1;
static const Word OPENSSL_X509_EX_V_NETSCAPE_HACK = 0x8000;
static const Shortint OPENSSL_X509_FILETYPE_DEFAULT = 0x3;
static const Shortint OPENSSL_X509_F_ADD_CERT_DIR = 0x64;
static const Shortint OPENSSL_X509_F_BY_FILE_CTRL = 0x65;
static const Shortint OPENSSL_X509_F_DIR_CTRL = 0x66;
static const Shortint OPENSSL_X509_F_GET_CERT_BY_SUBJECT = 0x67;
static const Shortint OPENSSL_X509_F_X509V3_ADD_EXT = 0x68;
static const System::Byte OPENSSL_X509_F_X509_CHECK_PRIVATE_KEY = 0x80;
static const Shortint OPENSSL_X509_F_X509_EXTENSION_CREATE_BY_NID = 0x6c;
static const Shortint OPENSSL_X509_F_X509_EXTENSION_CREATE_BY_OBJ = 0x6d;
static const Shortint OPENSSL_X509_F_X509_GET_PUBKEY_PARAMETERS = 0x6e;
static const Shortint OPENSSL_X509_F_X509_LOAD_CERT_FILE = 0x6f;
static const Shortint OPENSSL_X509_F_X509_LOAD_CRL_FILE = 0x70;
static const Shortint OPENSSL_X509_F_X509_NAME_ADD_ENTRY = 0x71;
static const Shortint OPENSSL_X509_F_X509_NAME_ENTRY_CREATE_BY_NID = 0x72;
static const Shortint OPENSSL_X509_F_X509_NAME_ENTRY_SET_OBJECT = 0x73;
static const Shortint OPENSSL_X509_F_X509_NAME_ONELINE = 0x74;
static const Shortint OPENSSL_X509_F_X509_NAME_PRINT = 0x75;
static const Shortint OPENSSL_X509_F_X509_PRINT_FP = 0x76;
static const Shortint OPENSSL_X509_F_X509_PUBKEY_GET = 0x77;
static const Shortint OPENSSL_X509_F_X509_PUBKEY_SET = 0x78;
static const Shortint OPENSSL_X509_F_X509_REQ_PRINT = 0x79;
static const Shortint OPENSSL_X509_F_X509_REQ_PRINT_FP = 0x7a;
static const Shortint OPENSSL_X509_F_X509_REQ_TO_X509 = 0x7b;
static const Shortint OPENSSL_X509_F_X509_STORE_ADD_CERT = 0x7c;
static const Shortint OPENSSL_X509_F_X509_STORE_ADD_CRL = 0x7d;
static const Shortint OPENSSL_X509_F_X509_TO_X509_REQ = 0x7e;
static const Shortint OPENSSL_X509_F_X509_VERIFY_CERT = 0x7f;
static const Shortint OPENSSL_X509_LU_CRL = 0x2;
static const Shortint OPENSSL_X509_LU_FAIL = 0x0;
static const Shortint OPENSSL_X509_LU_PKEY = 0x3;
static const Shortint OPENSSL_X509_LU_RETRY = 0xffffffff;
static const Shortint OPENSSL_X509_LU_X509 = 0x1;
static const Shortint OPENSSL_X509_L_ADD_DIR = 0x2;
static const Shortint OPENSSL_X509_L_FILE_LOAD = 0x1;
static const Shortint OPENSSL_X509_R_BAD_X509_FILETYPE = 0x64;
static const Shortint OPENSSL_X509_R_CANT_CHECK_DH_KEY = 0x72;
static const Shortint OPENSSL_X509_R_CERT_ALREADY_IN_HASH_TABLE = 0x65;
static const Shortint OPENSSL_X509_R_ERR_ASN1_LIB = 0x66;
static const Shortint OPENSSL_X509_R_INVALID_DIRECTORY = 0x71;
static const Shortint OPENSSL_X509_R_KEY_TYPE_MISMATCH = 0x73;
static const Shortint OPENSSL_X509_R_KEY_VALUES_MISMATCH = 0x74;
static const Shortint OPENSSL_X509_R_LOADING_CERT_DIR = 0x67;
static const Shortint OPENSSL_X509_R_LOADING_DEFAULTS = 0x68;
static const Shortint OPENSSL_X509_R_NO_CERT_SET_FOR_US_TO_VERIFY = 0x69;
static const Shortint OPENSSL_X509_R_SHOULD_RETRY = 0x6a;
static const Shortint OPENSSL_X509_R_UNABLE_TO_FIND_PARAMETERS_IN_CHAIN = 0x6b;
static const Shortint OPENSSL_X509_R_UNABLE_TO_GET_CERTS_PUBLIC_KEY = 0x6c;
static const Shortint OPENSSL_X509_R_UNKNOWN_KEY_TYPE = 0x75;
static const Shortint OPENSSL_X509_R_UNKNOWN_NID = 0x6d;
static const Shortint OPENSSL_X509_R_UNSUPPORTED_ALGORITHM = 0x6f;
static const Shortint OPENSSL_X509_R_WRONG_LOOKUP_TYPE = 0x70;
static const Shortint OPENSSL_X509_V_ERR_APPLICATION_VERIFICATION = 0x32;
static const Shortint OPENSSL_X509_V_ERR_CERT_CHAIN_TOO_LONG = 0x16;
static const Shortint OPENSSL_X509_V_ERR_CERT_HAS_EXPIRED = 0xa;
static const Shortint OPENSSL_X509_V_ERR_CERT_NOT_YET_VALID = 0x9;
static const Shortint OPENSSL_X509_V_ERR_CERT_REVOKED = 0x17;
static const Shortint OPENSSL_X509_V_ERR_CERT_SIGNATURE_FAILURE = 0x7;
static const Shortint OPENSSL_X509_V_ERR_CRL_HAS_EXPIRED = 0xc;
static const Shortint OPENSSL_X509_V_ERR_CRL_NOT_YET_VALID = 0xb;
static const Shortint OPENSSL_X509_V_ERR_CRL_SIGNATURE_FAILURE = 0x8;
static const Shortint OPENSSL_X509_V_ERR_DEPTH_ZERO_SELF_SIGNED_CERT = 0x12;
static const Shortint OPENSSL_X509_V_ERR_ERROR_IN_CERT_NOT_AFTER_FIELD = 0xe;
static const Shortint OPENSSL_X509_V_ERR_ERROR_IN_CERT_NOT_BEFORE_FIELD = 0xd;
static const Shortint OPENSSL_X509_V_ERR_ERROR_IN_CRL_LAST_UPDATE_FIELD = 0xf;
static const Shortint OPENSSL_X509_V_ERR_ERROR_IN_CRL_NEXT_UPDATE_FIELD = 0x10;
static const Shortint OPENSSL_X509_V_ERR_OUT_OF_MEM = 0x11;
static const Shortint OPENSSL_X509_V_ERR_SELF_SIGNED_CERT_IN_CHAIN = 0x13;
static const Shortint OPENSSL_X509_V_ERR_UNABLE_TO_DECODE_ISSUER_PUBLIC_KEY = 0x6;
static const Shortint OPENSSL_X509_V_ERR_UNABLE_TO_DECRYPT_CERT_SIGNATURE = 0x4;
static const Shortint OPENSSL_X509_V_ERR_UNABLE_TO_DECRYPT_CRL_SIGNATURE = 0x5;
static const Shortint OPENSSL_X509_V_ERR_UNABLE_TO_GET_CRL = 0x3;
static const Shortint OPENSSL_X509_V_ERR_UNABLE_TO_GET_ISSUER_CERT = 0x2;
static const Shortint OPENSSL_X509_V_ERR_UNABLE_TO_GET_ISSUER_CERT_LOCALLY = 0x14;
static const Shortint OPENSSL_X509_V_ERR_UNABLE_TO_VERIFY_LEAF_SIGNATURE = 0x15;
static const Shortint OPENSSL_X509_V_OK = 0x0;
static const Shortint OPENSSL_X509v3_KU_CRL_SIGN = 0x2;
static const Shortint OPENSSL_X509v3_KU_DATA_ENCIPHERMENT = 0x10;
static const Word OPENSSL_X509v3_KU_DECIPHER_ONLY = 0x8000;
static const System::Byte OPENSSL_X509v3_KU_DIGITAL_SIGNATURE = 0x80;
static const Shortint OPENSSL_X509v3_KU_ENCIPHER_ONLY = 0x1;
static const Shortint OPENSSL_X509v3_KU_KEY_AGREEMENT = 0x8;
static const Shortint OPENSSL_X509v3_KU_KEY_CERT_SIGN = 0x4;
static const Shortint OPENSSL_X509v3_KU_KEY_ENCIPHERMENT = 0x20;
static const Shortint OPENSSL_X509v3_KU_NON_REPUDIATION = 0x40;
static const Word OPENSSL_X509v3_KU_UNDEF = 0xffff;
static const Shortint OPENSSL__ATEXIT_SIZE = 0x20;
static const Shortint OPENSSL__IOFBF = 0x0;
static const Shortint OPENSSL__IOLBF = 0x1;
static const Shortint OPENSSL__IONBF = 0x2;
static const Shortint OPENSSL__N_LISTS = 0x1e;
static const Shortint OPENSSL__MSS_WIN32 = 0x1;
static const Shortint OPENSSL__MSS_X86_ = 0x1;
static const Shortint OPENSSL___CYGWIN32__ = 0x1;
static const Shortint OPENSSL___CYGWIN__ = 0x1;
static const Shortint OPENSSL___GNUC_MINOR__ = 0x5b;
static const Shortint OPENSSL___GNUC__ = 0x2;
static const Word OPENSSL___SAPP = 0x100;
static const Shortint OPENSSL___SEOF = 0x20;
static const Shortint OPENSSL___SERR = 0x40;
static const Shortint OPENSSL___SLBF = 0x1;
static const System::Byte OPENSSL___SMBF = 0x80;
static const Word OPENSSL___SMOD = 0x2000;
static const Shortint OPENSSL___SNBF = 0x2;
static const Word OPENSSL___SNPT = 0x800;
static const Word OPENSSL___SOFF = 0x1000;
static const Word OPENSSL___SOPT = 0x400;
static const Shortint OPENSSL___SRD = 0x4;
static const Shortint OPENSSL___SRW = 0x10;
static const Word OPENSSL___SSTR = 0x200;
static const Shortint OPENSSL___STDC__ = 0x1;
static const Shortint OPENSSL___SWR = 0x8;
static const Shortint OPENSSL___WINNT = 0x1;
static const Shortint OPENSSL___WINNT__ = 0x1;
static const Shortint OPENSSL___i386 = 0x1;
static const Shortint OPENSSL___i386__ = 0x1;
static const Shortint OPENSSL___i586 = 0x1;
static const Shortint OPENSSL___i586__ = 0x1;
static const Shortint OPENSSL___pentium = 0x1;
static const Shortint OPENSSL___pentium__ = 0x1;
static const Shortint OPENSSL_i386 = 0x1;
static const Shortint OPENSSL_i586 = 0x1;
static const Shortint OPENSSL_pentium = 0x1;
extern PACKAGE int __cdecl (*IdSslCtxSetCipherList)(void * arg0, char * str);
extern PACKAGE void * __cdecl (*IdSslCtxNew)(void * meth);
extern PACKAGE void __cdecl (*IdSslCtxFree)(void * arg0);
extern PACKAGE int __cdecl (*IdSslSetFd)(void * s, int fd);
extern PACKAGE int __cdecl (*IdSslCtxUsePrivateKeyFile)(void * ctx, const char * _file, int _type);
extern PACKAGE int __cdecl (*IdSslCtxUseCertificateFile)(void * ctx, const char * _file, int _type);
	
extern PACKAGE void __cdecl (*IdSslLoadErrorStrings)(void);
extern PACKAGE char * __cdecl (*IdSslStateStringLong)(void * s);
extern PACKAGE void * __cdecl (*IdSslGetPeerCertificate)(void * s);
extern PACKAGE void __cdecl (*IdSslCtxSetVerify)(void * ctx, int mode, PFunction arg2);
extern PACKAGE void __cdecl (*IdSslCtxSetVerifyDepth)(void * ctx, int depth);
extern PACKAGE int __cdecl (*IdSslCtxGetVerifyDepth)(void * ctx);
extern PACKAGE void __cdecl (*IdSslCtxSetDefaultPasswdCb)(void * ctx, void * cb);
extern PACKAGE void __cdecl (*IdSslCtxSetDefaultPasswdCbUserdata)(void * ctx, void * u);
extern PACKAGE int __cdecl (*IdSslCtxCheckPrivateKeyFile)(void * ctx);
extern PACKAGE void * __cdecl (*IdSslNew)(void * ctx);
extern PACKAGE void __cdecl (*IdSslFree)(void * ssl);
extern PACKAGE int __cdecl (*IdSslAccept)(void * ssl);
extern PACKAGE int __cdecl (*IdSslConnect)(void * ssl);
extern PACKAGE int __cdecl (*IdSslRead)(void * ssl, char * buf, int num);
extern PACKAGE int __cdecl (*IdSslPeek)(void * ssl, char * buf, int num);
extern PACKAGE int __cdecl (*IdSslWrite)(void * ssl, const char * buf, int num);
extern PACKAGE int __cdecl (*IdSslGetError)(void * s, int ret_code);
extern PACKAGE void * __cdecl (*IdSslMethodV2)(void);
extern PACKAGE void * __cdecl (*IdSslMethodServerV2)(void);
extern PACKAGE void * __cdecl (*IdSslMethodClientV2)(void);
extern PACKAGE void * __cdecl (*IdSslMethodV3)(void);
extern PACKAGE void * __cdecl (*IdSslMethodServerV3)(void);
extern PACKAGE void * __cdecl (*IdSslMethodClientV3)(void);
extern PACKAGE void * __cdecl (*IdSslMethodV23)(void);
extern PACKAGE void * __cdecl (*IdSslMethodServerV23)(void);
extern PACKAGE void * __cdecl (*IdSslMethodClientV23)(void);
extern PACKAGE void * __cdecl (*IdSslMethodTLSV1)(void);
extern PACKAGE void * __cdecl (*IdSslMethodServerTLSV1)(void);
extern PACKAGE void * __cdecl (*IdSslMethodClientTLSV1)(void);
extern PACKAGE int __cdecl (*IdSslShutdown)(void * s);
extern PACKAGE void __cdecl (*IdSslSetConnectState)(void * s);
extern PACKAGE void __cdecl (*IdSslSetAcceptState)(void * s);
extern PACKAGE void __cdecl (*IdSslSetShutdown)(void * ssl, int mode);
extern PACKAGE int __cdecl (*IdSslCtxLoadVerifyLocations)(void * ctx, const char * CAfile, const char * 
	CApath);
extern PACKAGE void * __cdecl (*IdSslGetSession)(void * ssl);
extern PACKAGE int __cdecl (*IdSslAddSslAlgorithms)(void);
extern PACKAGE void __cdecl (*IdSslCtxSetInfoCallback)(void * ctx, PFunction cb);
extern PACKAGE void * __cdecl (*IdSslX509StoreCtxGetAppData)(void * ctx);
extern PACKAGE int __cdecl (*IdSslSessionGetId)(void * s, PPChar id, PInteger length);
extern PACKAGE int __cdecl (*IdSslSessionGetIdCtx)(void * s, PPChar id, PInteger length);
extern PACKAGE int __cdecl (*IdSslCtxGetVersion)(void * ctx);
extern PACKAGE int __cdecl (*IdSslCtxSetOptions)(void * ctx, int op);
extern PACKAGE char * __cdecl (*IdSslX509NameOneline)(void * a, char * buf, int size);
extern PACKAGE unsigned __cdecl (*IdSslX509NameHash)(void * x);
extern PACKAGE int __cdecl (*IdSslX509SetIssuerName)(void * x, void * name);
extern PACKAGE void * __cdecl (*IdSslX509GetIssuerName)(void * a);
extern PACKAGE int __cdecl (*IdSslX509SetSubjectName)(void * x, void * name);
extern PACKAGE void * __cdecl (*IdSslX509GetSubjectName)(void * a);
extern PACKAGE int __cdecl (*IdSslX509Digest)(void * data, void * _type, char * md, PUInteger len);
extern PACKAGE void * __cdecl (*IdSslEvpMd5)(void);
extern PACKAGE PASN1_UTCTIME __cdecl (*IdSslX509GetNotBefore)(void * x509);
extern PACKAGE PASN1_UTCTIME __cdecl (*IdSslX509GetNotAfter)(void * x509);
extern PACKAGE int __cdecl (*IdSslX509StoreCtxGetError)(void * ctx);
extern PACKAGE void __cdecl (*IdSslX509StoreCtxSetError)(void * ctx, int s);
extern PACKAGE int __cdecl (*IdSslX509StoreCtxGetErrorDepth)(void * ctx);
extern PACKAGE void * __cdecl (*IdSslX509StoreCtxGetCurrentCert)(void * ctx);
extern PACKAGE void __cdecl (*IdDES_set_odd_parity)(const System::Byte * Key);
extern PACKAGE int __cdecl (*IdDES_set_key)(const System::Byte * key, const des_ks_struct * schedule);
extern PACKAGE void __cdecl (*IdDES_ecb_encrypt)(const System::Byte * Input, const System::Byte * output, const des_ks_struct 
	* ks, int enc);
extern PACKAGE int __cdecl (*IdSSL_set_ex_data)(void * ssl, int idx, void * data);
extern PACKAGE void * __cdecl (*IdSSL_get_ex_data)(void * ssl, int idx);
extern PACKAGE void * __cdecl (*IdSSLLoadClientCAFile)(const char * _file);
extern PACKAGE void __cdecl (*IdSSLCtxSetClientCAList)(void * ctx, void * list);
extern PACKAGE int __cdecl (*IdSSLCtxSetDefaultVerifyPaths)(void * ctx);
extern PACKAGE int __cdecl (*IdSSLCtxSetSessionIdContext)(void * ctx, const char * sid_ctx, int sid_ctx_len
	);
extern PACKAGE char * __cdecl (*IdSSLCipherDescription)(void * arg0, char * buf, int size);
extern PACKAGE void * __cdecl (*IdSSLGetCurrentCipher)(void * s);
extern PACKAGE char * __cdecl (*IdSSLCipherGetName)(void * c);
extern PACKAGE char * __cdecl (*IdSSLCipherGetVersion)(void * c);
extern PACKAGE int __cdecl (*IdSSLCipherGetBits)(void * c, PInteger alg_bits);
extern PACKAGE bool __fastcall Load(void);
extern PACKAGE void __fastcall Unload(void);
extern PACKAGE AnsiString __fastcall WhichFailedToLoad();
extern PACKAGE int __fastcall IdSslUCTTimeDecode(PASN1_UTCTIME UCTtime, Word &year, Word &month, Word 
	&day, Word &hour, Word &min, Word &sec, int &tz_hour, int &tz_min);
extern PACKAGE int __fastcall IdSslSetAppData(void * s, void * arg);
extern PACKAGE void * __fastcall IdSslGetAppData(void * s);
extern PACKAGE void __fastcall InitializeRandom(void);

}	/* namespace Idsslopensslheaders */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idsslopensslheaders;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdSSLOpenSSLHeaders
