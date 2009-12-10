// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdAssignedNumbers.pas' rev: 5.00

#ifndef IdAssignedNumbersHPP
#define IdAssignedNumbersHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Idassignednumbers
{
//-- type declarations -------------------------------------------------------
//-- var, const, procedure ---------------------------------------------------
static const Shortint IdPORT_TCPMUX = 0x1;
static const Shortint IdPORT_COMPRESSNET_MGM = 0x2;
static const Shortint IdPORT_COMPRESSNET_CMP = 0x3;
static const Shortint IdPORT_RJE = 0x5;
static const Shortint IdPORT_ECHO = 0x7;
static const Shortint IdPORT_DISCARD = 0x9;
static const Shortint IdPORT_SYSTAT = 0xb;
static const Shortint IdPORT_DAYTIME = 0xd;
static const Shortint IdPORT_NETSTAT = 0xf;
static const Shortint IdPORT_QOTD = 0x11;
static const Shortint IdPORT_MSP = 0x12;
static const Shortint IdPORT_CHARGEN = 0x13;
static const Shortint IdPORT_FTP_DATA = 0x14;
static const Shortint IdPORT_FTP = 0x15;
static const Shortint IdPORT_SSH = 0x16;
static const Shortint IdPORT_TELNET = 0x17;
static const Shortint IdPORT_ANYTERMINIL = 0x18;
static const Shortint IdPORT_SMTP = 0x19;
static const Shortint IdPORT_NSW_FE = 0x1b;
static const Shortint IdPORT_MSG_ICMP = 0x1d;
static const Shortint IdPORT_MSG_AUTH = 0x1f;
static const Shortint IdPORT_DSP = 0x21;
static const Shortint IdPORT_ANYPRINTER = 0x22;
static const Shortint IdPORT_TIME = 0x25;
static const Shortint IdPORT_RAP = 0x26;
static const Shortint IdPORT_RLP = 0x27;
static const Shortint IdPORT_GRAPHICS = 0x29;
static const Shortint IdPORT_NAMESERVER = 0x2a;
static const Shortint IdPORT_WHOIS = 0x2b;
static const Shortint IdPORT_MPM_FLAGS = 0x2c;
static const Shortint IdPORT_MPM = 0x2d;
static const Shortint IdPORT_MPM_SND = 0x2e;
static const Shortint IdPORT_NI_FTP = 0x2f;
static const Shortint IdPORT_AUDITD = 0x30;
static const Shortint IdPORT_BBN_LOGIN = 0x31;
static const Shortint IdPORT_RE_MAIL_CK = 0x32;
static const Shortint IdPORT_LA_MAINT = 0x33;
static const Shortint IdPORT_XNS_TIME = 0x34;
static const Shortint IdPORT_DOMAIN = 0x35;
static const Shortint IdPORT_XNS_CH = 0x36;
static const Shortint IdPORT_ISI_GL = 0x37;
static const Shortint IdPORT_XNS_AUTH = 0x38;
static const Shortint IdPORT_ANYPRIVATE_TERMINAL = 0x39;
static const Shortint IdPORT_XNS_MAIL = 0x3a;
static const Shortint IdPORT_ANY_FILE = 0x3b;
static const Shortint IdPORT_NI_MAIL = 0x3d;
static const Shortint IdPORT_ACAS = 0x3e;
static const Shortint IdPORT_WHOIS_PLUS = 0x3f;
static const Shortint IdPORT_COVIA = 0x40;
static const Shortint IdPORT_TACACS_DS = 0x41;
static const Shortint IdPORT_SQLNET = 0x42;
static const Shortint IdPORT_BOOTPS = 0x43;
static const Shortint IdPORT_BOOTPC = 0x44;
static const Shortint IdPORT_TFTP = 0x45;
static const Shortint IdPORT_GOPHER = 0x46;
static const Shortint IdPORT_NETRJS1 = 0x47;
static const Shortint IdPORT_NETRJS2 = 0x48;
static const Shortint IdPORT_NETRJS3 = 0x49;
static const Shortint IdPORT_NETRJS4 = 0x4a;
static const Shortint IdPORT_ANYDIAL = 0x4b;
static const Shortint IdPORT_DEOS = 0x4c;
static const Shortint IdPORT_ANYRJE = 0x4d;
static const Shortint IdPORT_VETTCP = 0x4e;
static const Shortint IdPORT_FINGER = 0x4f;
static const Shortint IdPORT_HTTP = 0x50;
static const Shortint IdPORT_HOSTS2_NS = 0x51;
static const Shortint IdPORT_XFER = 0x52;
static const Shortint IdPORT_MIT_ML_DEV = 0x53;
static const Shortint IdPORT_CTF = 0x54;
static const Shortint IdPORT_MIT_ML_DEV2 = 0x55;
static const Shortint IdPORT_MFCOBOL = 0x56;
static const Shortint IdPORT_ANYTERMINALLINK = 0x57;
static const Shortint IdPORT_KERBEROS = 0x58;
static const Shortint IdPORT_SU_MIT_TG = 0x59;
static const Shortint IdPORT_DNSIX = 0x5a;
static const Shortint IdPORT_MIT_DOV = 0x5b;
static const Shortint IdPORT_NPP = 0x5c;
static const Shortint IdPORT_DCP = 0x5d;
static const Shortint IdPORT_OBJCALL = 0x5e;
static const Shortint IdPORT_SUPDUP = 0x5f;
static const Shortint IdPORT_DIXIE = 0x60;
static const Shortint IdPORT_SWIFT_RVF = 0x61;
static const Shortint IdPORT_TACNEWS = 0x62;
static const Shortint IdPORT_METAGRAM = 0x63;
static const Shortint IdPORT_NEWACCT = 0x64;
static const Shortint IdPORT_HOSTNAME = 0x65;
static const Shortint IdPORT_ISO_TSAP = 0x66;
static const Shortint IdPORT_GPPITNP = 0x67;
static const Shortint IdPORT_ACR_NAME = 0x68;
static const Shortint IdPORT_CSNET_NS = 0x69;
static const Shortint IdPORT_3COM_TSMUX = 0x6a;
static const Shortint IdPORT_RTELNET = 0x6b;
static const Shortint IdPORT_SNAGAS = 0x6c;
static const Shortint IdPORT_POP2 = 0x6d;
static const Shortint IdPORT_POP3 = 0x6e;
static const Shortint IdPORT_SUNRPC = 0x6f;
static const Shortint IdPORT_McIDAS = 0x70;
static const Shortint IdPORT_AUTH = 0x71;
static const Shortint IdPORT_AUDIONEWS = 0x72;
static const Shortint IdPORT_SFTP = 0x73;
static const Shortint IdPORT_ANSANOTIFY = 0x74;
static const Shortint IdPORT_UUCP_PATH = 0x75;
static const Shortint IdPORT_SQLSERV = 0x76;
static const Shortint IdPORT_NNTP = 0x77;
static const Shortint IdPORT_CFDPTKT = 0x78;
static const Shortint IdPORT_ERPC = 0x79;
static const Shortint IdPORT_SMAKYNET = 0x7a;
static const Shortint IdPORT_SNTP = 0x7b;
static const Shortint IdPORT_ANASTRADER = 0x7c;
static const Shortint IdPORT_LOCUS_MAP = 0x7d;
static const Shortint IdPORT_UNITARY = 0x7e;
static const Shortint IdPORT_locus_con = 0x7f;
static const System::Byte IdPORT_GSS_XLICEN = 0x80;
static const System::Byte IdPORT_PWDGEN = 0x81;
static const System::Byte IdPORT_CISCO_FNA = 0x82;
static const System::Byte IdPORT_cisco_tna = 0x83;
static const System::Byte IdPORT_cisco_sys = 0x84;
static const System::Byte IdPORT_statsrv = 0x85;
static const System::Byte IdPORT_ingres_net = 0x86;
static const System::Byte IdPORT_loc_srv = 0x87;
static const System::Byte IdPORT_profile = 0x88;
static const System::Byte IdPORT_netbios_ns = 0x89;
static const System::Byte IdPORT_netbios_dgm = 0x8a;
static const System::Byte IdPORT_netbios_ssn = 0x8b;
static const System::Byte IdPORT_emfis_data = 0x8c;
static const System::Byte IdPORT_emfis_cntl = 0x8d;
static const System::Byte IdPORT_bl_idm = 0x8e;
static const System::Byte IdPORT_IMAP4 = 0x8f;
static const System::Byte IdPORT_news = 0x90;
static const System::Byte IdPORT_uaac = 0x91;
static const System::Byte IdPORT_iso_tp0 = 0x92;
static const System::Byte IdPORT_iso_ip = 0x93;
static const System::Byte IdPORT_cronus = 0x94;
static const System::Byte IdPORT_aed_512 = 0x95;
static const System::Byte IdPORT_sql_net = 0x96;
static const System::Byte IdPORT_hems = 0x97;
static const System::Byte IdPORT_bftp = 0x98;
static const System::Byte IdPORT_sgmp = 0x99;
static const System::Byte IdPORT_netsc_prod = 0x9a;
static const System::Byte IdPORT_netsc_dev = 0x9b;
static const System::Byte IdPORT_sqlsrv = 0x9c;
static const System::Byte IdPORT_knet_cmp = 0x9d;
static const System::Byte IdPORT_pcmail_srv = 0x9e;
static const System::Byte IdPORT_nss_routing = 0x9f;
static const System::Byte IdPORT_sgmp_traps = 0xa0;
static const System::Byte IdPORT_snmp = 0xa1;
static const System::Byte IdPORT_snmptrap = 0xa2;
static const System::Byte IdPORT_cmip_man = 0xa3;
static const System::Byte IdPORT_cmip_agent = 0xa4;
static const System::Byte IdPORT_xns_courier = 0xa5;
static const System::Byte IdPORT_s_net = 0xa6;
static const System::Byte IdPORT_namp = 0xa7;
static const System::Byte IdPORT_rsvd = 0xa8;
static const System::Byte IdPORT_send = 0xa9;
static const System::Byte IdPORT_print_srv = 0xaa;
static const System::Byte IdPORT_multiplex = 0xab;
static const System::Byte IdPORT_cl_1 = 0xac;
static const System::Byte IdPORT_xyplex_mux = 0xad;
static const System::Byte IdPORT_mailq = 0xae;
static const System::Byte IdPORT_vmnet = 0xaf;
static const System::Byte IdPORT_genrad_mux = 0xb0;
static const System::Byte IdPORT_xdmcp = 0xb1;
static const System::Byte IdPORT_NextStep = 0xb2;
static const System::Byte IdPORT_bgp = 0xb3;
static const System::Byte IdPORT_ris = 0xb4;
static const System::Byte IdPORT_unify = 0xb5;
static const System::Byte IdPORT_audit = 0xb6;
static const System::Byte IdPORT_ocbinder = 0xb7;
static const System::Byte IdPORT_ocserver = 0xb8;
static const System::Byte IdPORT_remote_kis = 0xb9;
static const System::Byte IdPORT_kis = 0xba;
static const System::Byte IdPORT_aci = 0xbb;
static const System::Byte IdPORT_mumps = 0xbc;
static const System::Byte IdPORT_qft = 0xbd;
static const System::Byte IdPORT_gacp = 0xbe;
static const System::Byte IdPORT_prospero = 0xbf;
static const System::Byte IdPORT_osu_nms = 0xc0;
static const System::Byte IdPORT_srmp = 0xc1;
static const System::Byte IdPORT_dn6_nlm_aud = 0xc3;
static const System::Byte IdPORT_dn6_smm_red = 0xc4;
static const System::Byte IdPORT_dls = 0xc5;
static const System::Byte IdPORT_dls_mon = 0xc6;
static const System::Byte IdPORT_smux = 0xc7;
static const System::Byte IdPORT_src = 0xc8;
static const System::Byte IdPORT_at_rtmp = 0xc9;
static const System::Byte IdPORT_at_nbp = 0xca;
static const System::Byte IdPORT_at_3 = 0xcb;
static const System::Byte IdPORT_at_echo = 0xcc;
static const System::Byte IdPORT_at_5 = 0xcd;
static const System::Byte IdPORT_at_zis = 0xce;
static const System::Byte IdPORT_at_7 = 0xcf;
static const System::Byte IdPORT_at_8 = 0xd0;
static const System::Byte IdPORT_qmtp = 0xd1;
static const System::Byte IdPORT_z39_50 = 0xd2;
static const System::Byte IdPORT_914c_g = 0xd3;
static const System::Byte IdPORT_anet = 0xd4;
static const System::Byte IdPORT_ipx = 0xd5;
static const System::Byte IdPORT_vmpwscs = 0xd6;
static const System::Byte IdPORT_softpc = 0xd7;
static const System::Byte IdPORT_CAIlic = 0xd8;
static const System::Byte IdPORT_dbase = 0xd9;
static const System::Byte IdPORT_mpp = 0xda;
static const System::Byte IdPORT_uarps = 0xdb;
static const System::Byte IdPORT_imap3 = 0xdc;
static const System::Byte IdPORT_fln_spx = 0xdd;
static const System::Byte IdPORT_rsh_spx = 0xde;
static const System::Byte IdPORT_cdc = 0xdf;
static const System::Byte Id_PORT_masqdialer = 0xe0;
static const System::Byte Id_PORT_direct = 0xf2;
static const System::Byte IdPORT_sur_meas = 0xf3;
static const System::Byte Id_PORT_inbusiness = 0xf4;
static const System::Byte IdPORT_link = 0xf5;
static const System::Byte IdPORT_dsp3270 = 0xf6;
static const Word IdPORT_pdap = 0x158;
static const Word IdPORT_pawserv = 0x159;
static const Word IdPORT_zserv = 0x15a;
static const Word IdPORT_fatserv = 0x15b;
static const Word IdPORT_csi_sgwp = 0x15c;
static const Word Id_PORT_mftp = 0x15d;
static const Word Id_PORT_matip_type_a = 0x15e;
static const Word Id_PORT_matip_type_b = 0x15f;
static const Word Id_PORT_dtag_ste_sb = 0x160;
static const Word Id_PORT_ndsauth = 0x161;
static const Word Id_PORT_bh611 = 0x162;
static const Word Id_PORT_datex_asn = 0x163;
static const Word Id_PORT_cloanto_net_1 = 0x164;
static const Word Id_PORT_bhevent = 0x165;
static const Word Id_PORT_shrinkwrap = 0x166;
static const Word Id_PORT_nsrmp = 0x167;
static const Word Id_PORT_scoi2odialog = 0x168;
static const Word Id_PORT_semantix = 0x169;
static const Word Id_PORT_srssend = 0x16a;
static const Word Id_PORT_rsvp_tunnel = 0x16b;
static const Word Id_PORT_aurora_cmgr = 0x16c;
static const Word Id_PORT_dtk = 0x16d;
static const Word Id_PORT_odmr = 0x16e;
static const Word Id_PORT_mortgageware = 0x16f;
static const Word Id_PORT_qbikgdp = 0x170;
static const Word Id_PORT_rpc2portmap = 0x171;
static const Word Id_PORT_codaauth2 = 0x172;
static const Word IdPORT_clearcase = 0x173;
static const Word IdPORT_ulistserv = 0x174;
static const Word IdPORT_legent_1 = 0x175;
static const Word IdPORT_legent_2 = 0x176;
static const Word IdPORT_hassle = 0x177;
static const Word IdPORT_nip = 0x178;
static const Word IdPORT_tnETOS = 0x179;
static const Word IdPORT_dsETOS = 0x17a;
static const Word IdPORT_is99c = 0x17b;
static const Word IdPORT_is99s = 0x17c;
static const Word IdPORT_hp_collector = 0x17d;
static const Word IdPORT_hp_managed_node = 0x17e;
static const Word IdPORT_hp_alarm_mgr = 0x17f;
static const Word IdPORT_arns = 0x180;
static const Word IdPORT_ibm_app = 0x181;
static const Word IdPORT_asa = 0x182;
static const Word IdPORT_aurp = 0x183;
static const Word IdPORT_unidata_ldm = 0x184;
static const Word IdPORT_ldap = 0x185;
static const Word IdPORT_uis = 0x186;
static const Word IdPORT_synotics_relay = 0x187;
static const Word IdPORT_synotics_broker = 0x188;
static const Word IdPORT_dis = 0x189;
static const Word IdPORT_embl_ndt = 0x18a;
static const Word IdPORT_etcp = 0x18b;
static const Word IdPORT_netware_ip = 0x18c;
static const Word IdPORT_mptn = 0x18d;
static const Word IdPORT_kryptolan = 0x18e;
static const Word IdPORT_iso_tsap_c2 = 0x18f;
static const Word IdPORT_work_sol = 0x190;
static const Word IdPORT_ups = 0x191;
static const Word IdPORT_genie = 0x192;
static const Word IdPORT_decap = 0x193;
static const Word IdPORT_nced = 0x194;
static const Word IdPORT_ncld = 0x195;
static const Word IdPORT_imsp = 0x196;
static const Word IdPORT_timbuktu = 0x197;
static const Word IdPORT_prm_sm = 0x198;
static const Word IdPORT_prm_nm = 0x199;
static const Word IdPORT_decladebug = 0x19a;
static const Word IdPORT_rmt = 0x19b;
static const Word IdPORT_synoptics_trap = 0x19c;
static const Word IdPORT_smsp = 0x19d;
static const Word IdPORT_infoseek = 0x19e;
static const Word IdPORT_bnet = 0x19f;
static const Word IdPORT_silverplatter = 0x1a0;
static const Word IdPORT_onmux = 0x1a1;
static const Word IdPORT_hyper_g = 0x1a2;
static const Word IdPORT_ariel1 = 0x1a3;
static const Word IdPORT_smpte = 0x1a4;
static const Word IdPORT_ariel2 = 0x1a5;
static const Word IdPORT_ariel3 = 0x1a6;
static const Word IdPORT_opc_job_start = 0x1a7;
static const Word IdPORT_opc_job_track = 0x1a8;
static const Word IdPORT_icad_el = 0x1a9;
static const Word IdPORT_smartsdp = 0x1aa;
static const Word IdPORT_svrloc = 0x1ab;
static const Word IdPORT_ocs_cmu = 0x1ac;
static const Word IdPORT_ocs_amu = 0x1ad;
static const Word IdPORT_utmpsd = 0x1ae;
static const Word IdPORT_utmpcd = 0x1af;
static const Word IdPORT_iasd = 0x1b0;
static const Word IdPORT_nnsp = 0x1b1;
static const Word IdPORT_mobileip_agent = 0x1b2;
static const Word IdPORT_mobilip_mn = 0x1b3;
static const Word IdPORT_dna_cml = 0x1b4;
static const Word IdPORT_comscm = 0x1b5;
static const Word IdPORT_dsfgw = 0x1b6;
static const Word IdPORT_dasp = 0x1b7;
static const Word IdPORT_sgcp = 0x1b8;
static const Word IdPORT_decvms_sysmgt = 0x1b9;
static const Word IdPORT_cvc_hostd = 0x1ba;
static const Word IdPORT_SSL = 0x1bb;
static const Word IdPORT_npp2 = 0x1bc;
static const Word IdPORT_microsoft_ds = 0x1bd;
static const Word IdPORT_ddm_rdb = 0x1be;
static const Word IdPORT_ddm_dfm = 0x1bf;
static const Word IdPORT_ddm_Byte = 0x1c0;
static const Word IdPORT_as_servermap = 0x1c1;
static const Word IdPORT_tserver = 0x1c2;
static const Word IdPORT_sfs_smp_net = 0x1c3;
static const Word IdPORT_sfs_config = 0x1c4;
static const Word IdPORT_creativeserver = 0x1c5;
static const Word IdPORT_contentserver = 0x1c6;
static const Word IdPORT_creativepartnr = 0x1c7;
static const Word IdPORT_macon_tcp = 0x1c8;
static const Word IdPORT_scohelp = 0x1c9;
static const Word IdPORT_appleqtc = 0x1ca;
static const Word IdPORT_ampr_rcmd = 0x1cb;
static const Word IdPORT_skronk = 0x1cc;
static const Word IdPORT_datasurfsrv = 0x1cd;
static const Word IdPORT_datasurfsrvsec = 0x1ce;
static const Word IdPORT_alpes = 0x1cf;
static const Word IdPORT_kpasswd = 0x1d0;
static const Word IdPORT_ssmtp = 0x1d1;
static const Word IdPORT_digital_vrc = 0x1d2;
static const Word IdPORT_mylex_mapd = 0x1d3;
static const Word IdPORT_photuris = 0x1d4;
static const Word IdPORT_rcp = 0x1d5;
static const Word IdPORT_scx_proxy = 0x1d6;
static const Word IdPORT_mondex = 0x1d7;
static const Word IdPORT_ljk_login = 0x1d8;
static const Word IdPORT_hybrid_pop = 0x1d9;
static const Word IdPORT_tn_tl_w1 = 0x1da;
static const Word IdPORT_tn_tl_w2 = 0x1da;
static const Word IdPORT_tcpnethaspsrv = 0x1db;
static const Word IdPORT_tn_tl_fd1 = 0x1dc;
static const Word IdPORT_ss7ns = 0x1dd;
static const Word IdPORT_spsc = 0x1de;
static const Word IdPORT_iafserver = 0x1df;
static const Word IdPORT_iafdbase = 0x1e0;
static const Word IdPORT_ph = 0x1e1;
static const Word IdPORT_bgs_nsi = 0x1e2;
static const Word IdPORT_ulpnet = 0x1e3;
static const Word IdPORT_integra_sme = 0x1e4;
static const Word IdPORT_powerburst = 0x1e5;
static const Word IdPORT_avian = 0x1e6;
static const Word IdPORT_saft = 0x1e7;
static const Word IdPORT_gss_http = 0x1e8;
static const Word IdPORT_nest_protocol = 0x1e9;
static const Word IdPORT_micom_pfs = 0x1ea;
static const Word IdPORT_go_login = 0x1eb;
static const Word IdPORT_ticf_1 = 0x1ec;
static const Word IdPORT_ticf_2 = 0x1ed;
static const Word IdPORT_pov_ray = 0x1ee;
static const Word IdPORT_intecourier = 0x1ef;
static const Word Id_PORT_pim_rp_disc = 0x1f0;
static const Word Id_PORT_dantz = 0x1f1;
static const Word Id_PORT_siam = 0x1f2;
static const Word Id_PORT_ISO_ILL = 0x1f3;
static const Word Id_PORT_isakmp = 0x1f4;
static const Word Id_PORT_stmf = 0x1f5;
static const Word Id_PORT_asa_appl_proto = 0x1f6;
static const Word Id_PORT_intrinsa = 0x1f7;
static const Word Id_PORT_citadel = 0x1f8;
static const Word Id_PORT_mailbox_lm = 0x1f9;
static const Word Id_PORT_ohimsrv = 0x1fa;
static const Word Id_PORT_crs = 0x1fb;
static const Word Id_PORT_xvttp = 0x1fc;
static const Word Id_PORT_snare = 0x1fd;
static const Word Id_PORT_FirstClass = 0x1fe;
static const Word Id_PORT_passgo = 0x1ff;
static const Word Id_PORT_exec = 0x200;
static const Word Id_PORT_biff = 0x200;
static const Word Id_PORT_login = 0x201;
static const Word IdPORT_who = 0x201;
static const Word IdPORT_cmd = 0x202;
static const Word IdPORT_syslog = 0x202;
static const Word IdPORT_LPD = 0x203;
static const Word IdPORT_talk = 0x205;
static const Word IdPORT_ntalk = 0x206;
static const Word IdPORT_utime = 0x207;
static const Word IdPORT_efs = 0x208;
static const Word IdPORT_router = 0x208;
static const Word IdPORT_timed = 0x20d;
static const Word IdPORT_tempo = 0x20e;
static const Word IdPORT_courier = 0x212;
static const Word IdPORT_conference = 0x213;
static const Word IdPORT_netnews = 0x214;
static const Word IdPORT_netwall = 0x215;
static const Word IdPORT_apertus_ldp = 0x21b;
static const Word IdPORT_uucp = 0x21c;
static const Word IdPORT_uucp_rlogin = 0x21d;
static const Word IdPORT_klogin = 0x21f;
static const Word IdPORT_kshell = 0x220;
static const Word IdPORT_appleqtcsrvr = 0x221;
static const Word IdPORT_dhcp_client_v6 = 0x222;
static const Word IdPORT_dhcp_server_v6 = 0x223;
static const Word Id_PORT_afpovertcp = 0x224;
static const Word Id_PORT_idfp = 0x225;
static const Word IdPORT_new_rwho = 0x226;
static const Word IdPORT_cybercash = 0x227;
static const Word IdPORT_deviceshare = 0x228;
static const Word IdPORT_pirp = 0x229;
static const Word IdPORT_rtsp = 0x22a;
static const Word IdPORT_dsf = 0x22b;
static const Word IdPORT_remotefs = 0x22c;
static const Word IdPORT_openvms_sysipc = 0x22d;
static const Word IdPORT_sdnskmp = 0x22e;
static const Word IdPORT_teedtap = 0x22f;
static const Word IdPORT_rmonitor = 0x230;
static const Word IdPORT_monitor = 0x231;
static const Word IdPORT_chshell = 0x232;
static const Word IdPORT_SNEWS = 0x233;
static const Word IdPORT_9pfs = 0x234;
static const Word IdPORT_whoami = 0x235;
static const Word IdPORT_streettalk = 0x236;
static const Word IdPORT_banyan_rpc = 0x237;
static const Word IdPORT_ms_shuttle = 0x238;
static const Word IdPORT_ms_rome = 0x239;
static const Word IdPORT_meter = 0x23a;
static const Word IdPORT_meter_udemon = 0x23b;
static const Word IdPORT_sonar = 0x23c;
static const Word IdPORT_banyan_vip = 0x23d;
static const Word IdPORT_ftp_agent = 0x23e;
static const Word IdPORT_vemmi = 0x23f;
static const Word Id_PORT_ipcd = 0x240;
static const Word Id_PORT_vnas = 0x241;
static const Word Id_PORT_ipdd = 0x242;
static const Word Id_PORT_decbsrv = 0x243;
static const Word Id_PORT_sntp_heartbeat = 0x244;
static const Word Id_PORT_bdp = 0x245;
static const Word Id_PORT_scc_security = 0x246;
static const Word Id_PORT_philips_vc = 0x247;
static const Word Id_PORT_keyserver = 0x248;
static const Word Id_PORT_imap4_ssl_dp = 0x249;
static const Word Id_PORT_password_chg = 0x24a;
static const Word Id_PORT_submission = 0x24b;
static const Word Id_PORT_cal = 0x24c;
static const Word Id_PORT_eyelink = 0x24d;
static const Word Id_PORT_tns_cml = 0x24e;
static const Word Id_PORT_http_alt = 0x24f;
static const Word Id_PORT_eudora_set = 0x250;
static const Word Id_PORT_http_rpc_epmap = 0x251;
static const Word Id_PORT_tpip = 0x252;
static const Word Id_PORT_cab_protocol = 0x253;
static const Word Id_PORT_smsd = 0x254;
static const Word Id_PORT_ptcnameservice = 0x255;
static const Word Id_PORT_sco_websrvrmg3 = 0x256;
static const Word Id_PORT_acp = 0x257;
static const Word IdPORT_ipcserver = 0x258;
static const Word Id_PORT_syslog_conn = 0x259;
static const Word IdPORT_urm = 0x25e;
static const Word IdPORT_nqs = 0x25f;
static const Word IdPORT_sift_uft = 0x260;
static const Word IdPORT_npmp_trap = 0x261;
static const Word IdPORT_npmp_local = 0x262;
static const Word IdPORT_npmp_gui = 0x263;
static const Word Id_PORT_hmmp_ind = 0x264;
static const Word Id_PORT_hmmp_op = 0x265;
static const Word Id_PORT_sshell = 0x266;
static const Word Id_PORT_sco_inetmgr = 0x267;
static const Word Id_PORT_sco_sysmgr = 0x268;
static const Word Id_PORT_sco_dtmgr = 0x269;
static const Word Id_PORT_dei_icda = 0x26a;
static const Word Id_PORT_compaq_evm = 0x26b;
static const Word Id_PORT_sco_websrvrmgr = 0x26c;
static const Word Id_PORT_escp_ip = 0x26d;
static const Word Id_PORT_collaborator = 0x26e;
static const Word Id_PORT_aux_bus_shunt = 0x26f;
static const Word Id_PORT_cryptoadmin = 0x270;
static const Word Id_PORT_dec_dlm = 0x271;
static const Word Id_PORT_asia = 0x272;
static const Word Id_PORT_passgo_tivoli = 0x273;
static const Word Id_PORT_qmqp = 0x274;
static const Word Id_PORT_3com_amp3 = 0x275;
static const Word Id_PORT_rda = 0x276;
static const Word Id_PORT_ipp = 0x277;
static const Word Id_PORT_bmpp = 0x278;
static const Word IdPORT_servstat = 0x279;
static const Word IdPORT_ginad = 0x27a;
static const Word Id_PORT_rlzdbase = 0x27b;
static const Word Id_PORT_ldaps = 0x27c;
static const Word Id_PORT_lanserver = 0x27d;
static const Word Id_PORT_mcns_sec = 0x27e;
static const Word Id_PORT_msdp = 0x27f;
static const Word Id_PORT_entrust_sps = 0x280;
static const Word Id_PORT_repcmd = 0x281;
static const Word Id_PORT_esro_emsdp = 0x282;
static const Word Id_PORT_sanity = 0x283;
static const Word Id_PORT_dwr = 0x284;
static const Word Id_PORT_pssc = 0x285;
static const Word Id_PORT_ldp = 0x286;
static const Word Id_PORT_dhcp_failover = 0x287;
static const Word Id_PORT_rrp = 0x288;
static const Word Id_PORT_aminet = 0x289;
static const Word Id_PORT_obex = 0x28a;
static const Word Id_PORT_ieee_mms = 0x28b;
static const Word Id_PORT_hello_port = 0x28c;
static const Word Id_PORT_repscmd = 0x28d;
static const Word Id_PORT_aodv = 0x28e;
static const Word Id_PORT_tinc = 0x28f;
static const Word Id_PORT_spmp = 0x290;
static const Word Id_PORT_rmc = 0x291;
static const Word Id_PORT_tenfold = 0x292;
static const Word Id_PORT_mac_srvr_admin = 0x294;
static const Word Id_PORT_hap = 0x295;
static const Word Id_PORT_pftp = 0x296;
static const Word Id_PORT_purenoise = 0x297;
static const Word Id_PORT_secure_aux_bus = 0x298;
static const Word Id_PORT_sun_dr = 0x299;
static const Word IdPORT_mdqs = 0x29a;
static const Word IdPORT_doom = 0x29a;
static const Word Id_PORT_disclose = 0x29b;
static const Word Id_PORT_mecomm = 0x29c;
static const Word Id_PORT_meregister = 0x29d;
static const Word Id_PORT_vacdsm_sws = 0x29e;
static const Word Id_PORT_vacdsm_app = 0x29f;
static const Word Id_PORT_vpps_qua = 0x2a0;
static const Word Id_PORT_cimplex = 0x2a1;
static const Word Id_PORT_acap = 0x2a2;
static const Word Id_PORT_dctp = 0x2a3;
static const Word Id_PORT_vpps_via = 0x2a4;
static const Word Id_PORT_vpp = 0x2a5;
static const Word Id_PORT_gnf_ncp = 0x2a6;
static const Word Id_PORT_mrm = 0x2a7;
static const Word Id_PORT_entrust_aaas = 0x2a8;
static const Word Id_PORT_entrust_aams = 0x2a9;
static const Word Id_PORT_xfr = 0x2aa;
static const Word Id_PORT_corba_iiop = 0x2ab;
static const Word Id_PORT_corba_iiop_ssl = 0x2ac;
static const Word Id_PORT_mdc_portmapper = 0x2ad;
static const Word Id_PORT_hcp_wismar = 0x2ae;
static const Word Id_PORT_asipregistry = 0x2af;
static const Word Id_PORT_realm_rusd = 0x2b0;
static const Word Id_PORT_nmap = 0x2b1;
static const Word Id_PORT_vatp = 0x2b2;
static const Word Id_PORT_msexch_routing = 0x2b3;
static const Word Id_PORT_hyperwave_isp = 0x2b4;
static const Word Id_PORT_connendp = 0x2b5;
static const Word Id_PORT_ha_cluster = 0x2b6;
static const Word Id_PORT_ieee_mms_ssl = 0x2b7;
static const Word Id_PORT_rushd = 0x2b8;
static const Word Id_PORT_uuidgen = 0x2b9;
static const Word Id_PORT_olsr = 0x2ba;
static const Word Id_PORT_accessnetwork = 0x2bb;
static const Word IdPORT_elcsd = 0x2c0;
static const Word Id_PORT_agentx = 0x2c1;
static const Word Id_PORT_silc = 0x2c2;
static const Word Id_PORT_borland_dsj = 0x2c3;
static const Word IdPORT_entrustmanager = 0x2c5;
static const Word Id_PORT_entrust_ash = 0x2c6;
static const Word Id_PORT_cisco_tdp = 0x2c7;
static const Word IdPORT_netviewdm1 = 0x2d9;
static const Word IdPORT_netviewdm2 = 0x2da;
static const Word IdPORT_netviewdm3 = 0x2db;
static const Word IdPORT_netgw = 0x2e5;
static const Word IdPORT_netrcs = 0x2e6;
static const Word IdPORT_flexlm = 0x2e8;
static const Word IdPORT_fujitsu_dev = 0x2eb;
static const Word IdPORT_ris_cm = 0x2ec;
static const Word IdPORT_kerberos_adm = 0x2ed;
static const Word IdPORT_rfile = 0x2ee;
static const Word IdPORT_loadav = 0x2ee;
static const Word IdPORT_pump = 0x2ef;
static const Word IdPORT_qrh = 0x2f0;
static const Word IdPORT_rrh = 0x2f1;
static const Word IdPORT_tell = 0x2f2;
static const Word IdPORT_nlogin = 0x2f6;
static const Word IdPORT_con = 0x2f7;
static const Word IdPORT_ns = 0x2f8;
static const Word IdPORT_rxe = 0x2f9;
static const Word IdPORT_quotad = 0x2fa;
static const Word IdPORT_cycleserv = 0x2fb;
static const Word IdPORT_omserv = 0x2fc;
static const Word IdPORT_webster = 0x2fd;
static const Word IdPORT_phonebook = 0x2ff;
static const Word IdPORT_vid = 0x301;
static const Word IdPORT_cadlock = 0x302;
static const Word IdPORT_rtip = 0x303;
static const Word IdPORT_cycleserv2 = 0x304;
static const Word IdPORT_submit = 0x305;
static const Word IdPORT_notify = 0x305;
static const Word IdPORT_rpasswd = 0x306;
static const Word IdPORT_acmaint_dbd = 0x306;
static const Word IdPORT_entomb = 0x307;
static const Word IdPORT_acmaint_transd = 0x307;
static const Word IdPORT_wpages = 0x308;
static const Word IdPORT_wpgs = 0x30c;
static const Word IdPORT_concert = 0x312;
static const Word IdPORT_qsc = 0x313;
static const Word IdPORT_mdbs_daemon = 0x320;
static const Word IdPORT_device = 0x321;
static const Word Id_PORT_fcp = 0x32a;
static const Word Id_PORT_itm_mcell_s = 0x33c;
static const Word Id_PORT_pkix_3_ca_ra = 0x33d;
static const Word Id_PORT_dhcp_failover2 = 0x34f;
static const Word Id_PORT_rsync = 0x369;
static const Word Id_PORT_iclcnet_locate = 0x376;
static const Word Id_PORT_iclcnet_svinfo = 0x377;
static const Word IdPORT_accessbuilder = 0x378;
static const Word Id_PORT_omginitialrefs = 0x384;
static const Word Id_PORT_smpnameres = 0x385;
static const Word Id_PORT_ideafarm_chat = 0x386;
static const Word Id_PORT_ideafarm_catch = 0x387;
static const Word IdPOPRT_xact_backup = 0x38f;
static const Word IdPORT_ftps_data = 0x3dd;
static const Word IdPORT_ftps = 0x3de;
static const Word IdPORT_nas = 0x3df;
static const Word IdPORT_TelnetS = 0x3e0;
static const Word IdPORT_IMAP4S = 0x3e1;
static const Word IdPORT_IRCS = 0x3e2;
static const Word IdPORT_POP3S = 0x3e3;
static const Word IdPORT_vsinet = 0x3e4;
static const Word IdPORT_maitrd = 0x3e5;
static const Word IdPORT_busboy = 0x3e6;
static const Word IdPORT_puparp = 0x3e6;
static const Word IdPORT_garcon = 0x3e7;
static const Word IdPORT_applix = 0x3e7;
static const Word IdPORT_puprouter = 0x3e7;
static const Word IdPORT_cadlock2 = 0x3e8;
static const Word IdPORT_ock = 0x3e8;
static const Word ID_PORT_surf = 0x3f2;
static const Word IdPORT_SOCKS = 0x438;
static const Word IdPORT_DICT = 0xa44;
static const Word IdPORT_IRC = 0x1a0b;
static const Shortint Id_AIVN_Rserved = 0x0;
static const Shortint Id_AIVN_IP = 0x4;
static const Shortint Id_AIVN_ST = 0x5;
static const Shortint Id_AIVN_SIP = 0x6;
static const Shortint Id_AIVN_TP_IX = 0x7;
static const Shortint Id_AIVN_PIP = 0x8;
static const Shortint Id_AIVN_Tuba = 0x9;
static const Shortint Id_AIVN_Reserved2 = 0xf;
static const Shortint Id_AIPN_Reserved = 0x0;
static const Shortint Id_AIPN_ICMP = 0x1;
static const Shortint Id_AIPN_IGMP = 0x2;
static const Shortint Id_AIPN_GGP = 0x3;
static const Shortint Id_AIPN_IP = 0x4;
static const Shortint Id_AIPN_ST = 0x5;
static const Shortint Id_AIPN_TCP = 0x6;
static const Shortint Id_AIPN_UCL = 0x7;
static const Shortint Id_AIPN_EGP = 0x8;
static const Shortint Id_AIPN_IGP = 0x9;
static const Shortint Id_AIPN_BBN_RCC_MON = 0xa;
static const Shortint Id_AIPN_NVP_II = 0xb;
static const Shortint Id_AIPN_PUP = 0xc;
static const Shortint Id_AIPN_ARGUS = 0xd;
static const Shortint Id_AIPN_EMCON = 0xe;
static const Shortint Id_AIPN_XNET = 0xf;
static const Shortint Id_AIPN_CHAOS = 0x10;
static const Shortint Id_AIPN_UDP = 0x11;
static const Shortint Id_AIPN_MUX = 0x12;
static const Shortint Id_AIPN_DCN_MEAS = 0x13;
static const Shortint Id_AIPN_HMP = 0x14;
static const Shortint Id_AIPN_PRM = 0x15;
static const Shortint Id_AIPN_XNS_IDP = 0x16;
static const Shortint Id_AIPN_TRUNK_1 = 0x17;
static const Shortint Id_AIPN_TRUNK_2 = 0x18;
static const Shortint Id_AIPN_LEAF_1 = 0x19;
static const Shortint Id_AIPN_LEAF_2 = 0x1a;
static const Shortint Id_AIPN_RDP = 0x1b;
static const Shortint Id_AIPN_IRTP = 0x1c;
static const Shortint Id_AIPN_ISO_TP4 = 0x1d;
static const Shortint Id_AIPN_NETBLT = 0x1e;
static const Shortint Id_AIPN_NFE_NSP = 0x1f;
static const Shortint Id_AIPN_MERIT_IMP = 0x20;
static const Shortint Id_AIPN_SEP = 0x21;
static const Shortint Id_AIPN_3PC = 0x22;
static const Shortint Id_AIPN_IDPR = 0x23;
static const Shortint Id_AIPN_XTP = 0x24;
static const Shortint Id_AIPN_DDP = 0x25;
static const Shortint Id_AIPN_IDPR_CMTP = 0x26;
static const Shortint Id_AIPN_TP_PLUS_PLUS = 0x27;
static const Shortint Id_AIPN_IL = 0x28;
static const Shortint Id_AIPN_SIP = 0x29;
static const Shortint Id_AIPN_SDRP = 0x2a;
static const Shortint Id_AIPN_SIP_SR = 0x2b;
static const Shortint Id_AIPN_SIP_FRAG = 0x2c;
static const Shortint Id_AIPN_IDRP = 0x2d;
static const Shortint Id_AIPN_RSVP = 0x2e;
static const Shortint Id_AIPN_GRE = 0x2f;
static const Shortint Id_AIPN_MHRP = 0x30;
static const Shortint Id_AIPN_BNA = 0x31;
static const Shortint Id_AIPN_SIPP_ESB = 0x32;
static const Shortint Id_AIPN_SIPP_AH = 0x33;
static const Shortint Id_AIPN_I_NLSP = 0x34;
static const Shortint Id_AIPN_SWIPE = 0x35;
static const Shortint Id_AIPN_NHRP = 0x36;
static const Shortint Id_AIPN_MOBILE = 0x37;
static const Shortint Id_AIPN_TLSP = 0x38;
static const Shortint Id_AIPN_Kryptonet = 0x3a;
static const Shortint Id_AIPN_SKIP = 0x39;
static const Shortint Id_AIPN_IPV6_ICMP = 0x3a;
static const Shortint Id_AIPN_IPV6_NO_NEXT = 0x3b;
static const Shortint Id_AIPN_IPV6_OPTS = 0x3c;
static const Shortint Id_AIPN_Any_Host_Internal = 0x3d;
static const Shortint Id_AIPN_CFTP = 0x3e;
static const Shortint Id_AIPN_Any_LAN = 0x3f;
static const Shortint Id_AIPN_SAT_EXPACK = 0x40;
static const Shortint Id_AIPN_KRYPTOLAN = 0x41;
static const Shortint Id_AIPN_RVD = 0x42;
static const Shortint Id_AIPN_IPPC = 0x43;
static const Shortint Id_AIPN_Any_Distributed_File_System = 0x44;
static const Shortint Id_AIPN_SAT_MON = 0x45;
static const Shortint Id_AIPN_VISA = 0x46;
static const Shortint Id_AIPN_IPCV = 0x47;
static const Shortint Id_AIPN_CPNX = 0x48;
static const Shortint Id_AIPN_CPHB = 0x49;
static const Shortint Id_AIPN_WSM = 0x4a;
static const Shortint Id_AIPN_PVP = 0x4b;
static const Shortint Id_AIPN_BR_SAT_MON = 0x4c;
static const Shortint Id_AIPN_SUN_ND = 0x4d;
static const Shortint Id_AIPN_WB_MON = 0x4e;
static const Shortint Id_AIPN_EXPAK = 0x4f;
static const Shortint Id_AIPN_ISO_IP = 0x50;
static const Shortint Id_AIPN_VMTP = 0x51;
static const Shortint Id_AIPN_SECURE_VMTP = 0x52;
static const Shortint Id_AIPN_VINES = 0x53;
static const Shortint Id_AIPN_TTP = 0x54;
static const Shortint Id_AIPN_NSFNET_IGP = 0x55;
static const Shortint Id_AIPN_DGP = 0x56;
static const Shortint Id_AIPN_TCF = 0x57;
static const Shortint Id_AIPN_IGRP = 0x58;
static const Shortint Id_AIPN_OSPFIGP = 0x59;
static const Shortint Id_AIPN_Sprite_RPC = 0x5a;
static const Shortint Id_AIPN_LARP = 0x5b;
static const Shortint Id_AIPN_MTP = 0x5c;
static const Shortint Id_AIPN_AX_25 = 0x5d;
static const Shortint Id_AIPN_IPIP = 0x5e;
static const Shortint Id_AIPN_MICP = 0x5f;
static const Shortint Id_AIPN_SCC_SP = 0x60;
static const Shortint Id_AIPN_ETHERIP = 0x61;
static const Shortint Id_AIPN_ENCAP = 0x62;
static const Shortint Id_AIPN_Any_Private_Encryption = 0x63;
static const Shortint Id_AIPN_GMTP = 0x64;
static const Shortint Id_AIPN_IFMP = 0x65;
static const Shortint Id_AIPN_PNNI = 0x66;
static const Shortint Id_AIPN_PIM = 0x67;
static const Shortint Id_AIPN_ARIS = 0x68;
static const Shortint Id_AIPN_SCPS = 0x69;
static const Shortint Id_AIPN_QNX = 0x6a;
static const Shortint Id_AIPN_A_N = 0x6b;
static const Shortint Id_AIPN_IPComp = 0x6c;
static const Shortint Id_AIPN_SNP = 0x6d;
static const Shortint Id_AIPN_Compaq_Peer = 0x6e;
static const Shortint Id_AIPN_IPX_In_IP = 0x6f;
static const Shortint Id_AIPN_VRRP = 0x70;
static const Shortint Id_AIPN_PGM = 0x71;
static const Shortint Id_AIPN_0_HOP = 0x72;
static const Shortint Id_AIPN_L2TP = 0x73;
static const Shortint Id_AIPN_DDX = 0x74;
static const Shortint Id_AIPN_IATP = 0x75;
static const Shortint Id_AIPN_STP = 0x76;
static const Shortint Id_AIPN_SRP = 0x77;
static const Shortint Id_AIPN_UTI = 0x78;
static const Shortint Id_AIPN_SMP = 0x79;
static const Shortint Id_AIPN_SM = 0x7a;
static const Shortint Id_AIPN_PTP = 0x7b;
static const Shortint Id_AIPN_ISIS = 0x7c;
static const Shortint Id_AIPN_FIRE = 0x7d;
static const Shortint Id_AIPN_CRTP = 0x7e;
static const Shortint Id_AIPN_CRUDP = 0x7f;
static const System::Byte Id_AIPN_SSCOPMCE = 0x80;
static const System::Byte Id_AIPN_IPLT = 0x81;
static const System::Byte Id_AIPN_SPS = 0x82;
static const System::Byte Id_AIPN_PIPE = 0x83;
static const System::Byte Id_AIPN_SCTP = 0x84;
static const System::Byte Id_AIPN_FC = 0x85;
static const System::Byte Id_AIPN_RSVP_E2E_IGNORE = 0x86;
static const System::Byte Id_AIPN_Reserved2 = 0xff;
#define Id_OS_Agis "AEGIS"
#define Id_OS_Amiga_1_2 "AMIGA-OS-1.2"
#define Id_OS_Amiga_1_3 "AMIGA-OS-1.3"
#define Id_OS_Amiga_2_0 "AMIGA-OS-2.0"
#define Id_OS_Amiga_2_1 "AMIGA-OS-2.1"
#define Id_OS_Amiga_3_0 "AMIGA-OS-3.0"
#define Id_OS_Amiga_3_1 "AMIGA-OS-3.1"
#define Id_OS_Apollo "APOLLO"
#define Id_OS_AIX_370 "AIX/370"
#define Id_OS_AIX_PS2 "AIX-PS/2"
#define Id_OS_BEOS_4_5_2 "BEOS-4.5.2"
#define Id_OS_BEOS_5_0 "BEOS-5.0"
#define Id_OS_BS_2000 "BS-2000"
#define Id_OS_Cedar "CEDAR"
#define Id_OS_CGW "CGW"
#define Id_OS_CHORUS "CHORUS"
#define Id_OS_Chrysalis "CHRYSALIS"
#define Id_OS_CMOS "CMOS"
#define Id_OS_CMS "CMS"
#define Id_OS_COS "COS"
#define Id_OS_CPIX "CPIX"
#define Id_OS_CTOS "CTOS"
#define Id_OS_CTSS "CTSS"
#define Id_OS_DCN "DCN"
#define Id_OS_DDNOS "DDNOS"
#define Id_OS_DOMAIN "DOMAIN"
#define Id_OS_DOS "DOS"
#define Id_OS_EDX "EDX"
#define Id_OS_ELF "ELF"
#define Id_OS_EMBOS "EMBOS"
#define Id_OS_EMMOS "EMMOS"
#define Id_OS_EPOS "EPOS"
#define Id_OS_FOONEX "FOONEX"
#define Id_OS_FORTH "FORTH"
#define Id_OS_FUZZ "FUZZ"
#define Id_OS_GCOS "GCOS"
#define Id_OS_GPOS "GPOS"
#define Id_OS_HDOS "HDOS"
#define Id_OS_Imagen "IMAGEN"
#define Id_OS_Instant_Internet "INSTANT-INTERNET"
#define Id_OS_Intercom "INTERCOM"
#define Id_OS_Impress "IMPRESS"
#define Id_OS_Interlisp "INTERLISP"
#define Id_OS_IOS "IOS"
#define Id_OS_IRIX "IRIX"
#define Id_OS_ISI "ISI-68020"
#define Id_OS_ITS "ITS"
#define Id_OS_KOSOS "KOSOS"
#define Id_OS_Linux "LINUX"
#define Id_OS_Linux_1_0 "LINUX-1.0"
#define Id_OS_Linux_1_2 "LINUX-1.2"
#define Id_OS_Linux_2_0 "LINUX-2.0"
#define Id_OS_Linux_2_2 "LINUX-2.2"
#define Id_OS_LISP "LISP"
#define Id_OS_LISPM "LISPM"
#define Id_OS_LOCUS "LOCUS"
#define Id_OS_MACOS "MACOS"
#define Id_OS_MINOS "MINOS"
#define Id_OS_MOS "MOS"
#define Id_OS_MPE5 "MPE5"
#define Id_OS_MPEV "MPE/V"
#define Id_OS_MPEIX "MPE/IX"
#define Id_OS_MSDOS "MSDOS"
#define Id_OS_MULTICS "MULTICS"
#define Id_OS_MUSIC "MUSIC"
#define Id_OS_MUSICSP "MUSIC/SP"
#define Id_OS_MVS "MVS"
#define Id_OS_MVSSP "MVS/SP"
#define Id_NET_BSD_1_0 "NETBSD-1.0"
#define Id_NET_BSD_1_1 "NETBSD-1.1"
#define Id_NET_BSD_1_2 "NETBSD-1.2"
#define Id_NET_BSD_1_3 "NETBSD-1.3"
#define Id_NET_BSD_3_0 "NETWARE-3"
#define Id_NET_BSD_3_11 "NETWARE-3.11"
#define Id_NET_BSD_4_0 "NETWARE-4.0"
#define Id_NET_BSD_4_1 "NETWARE-4.1"
#define Id_NET_BSD_5_0 "NETWARE-5.0"
#define Id_OS_NEXUS "NEXUS"
#define Id_OS_NMS "NMS"
#define Id_OS_NONSTOP "NONSTOP"
#define Id_OS_NOS_2 "NOS-2"
#define Id_OS_NTOS "NTOS"
#define Id_OS_OpenBSD "OPENBSD"
#define Id_OS_OpenVMS "OPENVMS"
#define Id_OS_OSDDP "OS/DDP"
#define Id_OS_OS_2 "OS/2"
#define Id_OS_OS_4 "OS4"
#define Id_OS_OS_6 "OS86"
#define Id_OS_OSX "OSX"
#define Id_OS_PCDOS "PCDOS"
#define Id_OS_PERQOS "PERQ/OS"
#define Id_OS_PLI "PLI"
#define Id_OS_PSDDOSMIT "PSDOS/MIT"
#define Id_OS_Primos "PRIMOS"
#define Id_OS_RISC_OS "RISC-OS"
#define Id_OS_RISC_OS_3_10 "RISC-OS-3.10"
#define Id_OS_RISC_OS_3_50 "RISC-OS-3.50"
#define Id_OS_RISC_OS_3_60 "RISC-OS-3.60"
#define Id_OS_RISC_OS_3_70 "RISC-OS-3.70"
#define Id_OS_RISC_OS_4_00 "RISC-OS-4.00"
#define Id_OS_RMXRDOS "RMX/RDOS"
#define Id_OS_ROS "ROS"
#define Id_OS_RSX11M "RSX11M"
#define Id_OS_RTE_A "RTE-A"
#define Id_OS_Satops "SATOPS"
#define Id_OS_Sinix "SINIX"
#define Id_OS_SCO_Open_Desktop_1_0 "SCO-OPEN-DESKTOP-1.0"
#define Id_OS_SCO_Open_Desktop_1_1 "SCO-OPEN-DESKTOP-1.1"
#define Id_OS_SCO_Open_Desktop_2_0 "SCO-OPEN-DESKTOP-2.0"
#define Id_OS_SCO_Open_Desktop_3_0 "SCO-OPEN-DESKTOP-3.0"
#define Id_OS_SCO_Open_Desktop_Lite_3_0 "SCO-OPEN-DESKTOP-LITE-3.0"
#define Id_OS_SCO_Open_Server_3_0 "SCO-OPEN-SERVER-3.0"
#define Id_OS_SCO_Unix_3_2_0 "SCO-UNIX-3.2.0"
#define Id_OS_SCO_Unix_3_2V2_0 "SCO-UNIX-3.2V2.0"
#define Id_OS_SCO_Unix_3_2V1_0 "SCO-UNIX-3.2V2.1"
#define Id_OS_SCO_Unix_S_2V4_0 "SCO-UNIX-3.2V4.0"
#define Id_OS_SCO_Unix_3_2V4_1 "SCO-UNIX-3.2V4.1"
#define Id_OS_SCO_Unix_3_2V4_2 "SCO-UNIX-3.2V4.2"
#define Id_OS_SCO_Xenix_386_2_3_2 "SCO-XENIX-386-2.3.2"
#define Id_OS_SCO_Xenix_386_2_3_3 "SCO-XENIX-386-2.3.3"
#define Id_OS_SCO_Xenix_386_2_3_4 "SCO-XENIX-386-2.3.4"
#define Id_OS_SCS "SCS"
#define Id_OS_SIMP "SIMP"
#define Id_OS_SUN "SUN"
#define Id_OS_SUN_OS_3_5 "SUN-OS-3.5"
#define Id_OS_SUN_OS_4_0 "SUN-OS-4.0"
#define Id_OS_Swift "SWIFT"
#define Id_OS_Tac "TAC"
#define Id_OS_Tandem "TANDEM"
#define Id_OS_Tenex "TENEX"
#define Id_OS_The_Major_BBS "THE-MAJOR-BBS"
#define Id_OS_Tops10 "TOPS10"
#define Id_OS_Tops20 "TOPS20"
#define Id_OS_TOS "TOS"
#define Id_OS_TP3010 "TP3010"
#define Id_OS_TRSDOS "TRSDOS"
#define Id_OS_Ultrix "ULTRIX"
#define Id_OS_Unix "UNIX"
#define Id_OS_Unix_BSD "UNIX-BSD"
#define Id_OS_Unix_V1AT "UNIX-V1AT"
#define Id_OS_Unix_V "UNIX-V"
#define Id_OS_Unix_V_1 "UNIX-V.1"
#define Id_OS_Unix_V_2 "UNIX-V.2"
#define Id_OS_Unix_V_3 "UNIX-V.3"
#define Id_OS_Unix_PC "UNIX-PC"
#define Id_OS_Unix_Unknown "UNKNOWN"
#define Id_OS_UT2D "UT2D"
static const char Id_OS_V = '\x56';
#define Id_OS_VM "VM"
#define Id_OS_VM_370 "VM/370"
#define Id_OS_VM_CMS "VM/CMS"
#define Id_OS_VM_SP "VM/SP"
#define Id_OS_VMS "VMS"
#define Id_OS_VMS_Eunice "VMS/EUNICE"
#define Id_OS_VRTX "VRTX"
#define Id_OS_Waits "WAITS"
#define Id_OS_Wang "WANG"
#define Id_OS_Win32 "WIN32"
#define Id_OS_Windows_95 "WINDOWS-95"
#define Id_OS_Windows_95OSR1 "WINDOWS-95-OSR1"
#define Id_OS_Windows_95OSR2 "WINDOWS-95-OSR2"
#define Id_OS_Windows_98 "WINDOWS-98"
#define Id_OS_Windows_CE "WINDOWS-CE"
#define Id_OS_Windows_NT "WINDOWS-NT"
#define Id_OS_Windows_NT_2 "WINDOWS-NT-2"
#define Id_OS_Windows_NT_3 "WINDOWS-NT-3"
#define Id_OS_Windows_NT_3_5 "WINDOWS-NT-3.5"
#define Id_OS_Windows_NT_3_51 "WINDOWS-NT-3.51"
#define Id_OS_Windows_NT_4 "WINDOWS-NT-4"
#define Id_OS_Windows_NT_5 "WINDOWS-NT-5"
#define Id_OS_WorldGroup "WORLDGROUP"
#define Id_OS_Wyse_Wyxware "WYSE-WYXWARE"
#define Id_OS_X11R3 "X11R3"
#define Id_OS_XDE "XDE"
#define Id_OS_Xenix "XENIX"
#define Id_MN_Amiga_500 "AMIGA-500"
#define Id_MN_Amiga_500_010 "AMIGA-500/010"
#define Id_MN_Amiga_500_020 "AMIGA-500/020"
#define Id_MN_Amiga_500_EC030 "AMIGA-500/EC030"
#define Id_MN_Amiga_500_030 "AMIGA-500/030"
#define Id_MN_Amiga_600 "AMIGA-600"
#define Id_MN_Amiga_1000 "AMIGA-1000"
#define Id_MN_Amiga_1000_010 "AMIGA-1000/010"
#define Id_MN_Amiga_1000_020 "AMIGA-1000/020"
#define Id_MN_Amiga_1000_EC030 "AMIGA-1000/EC030"
#define Id_MN_Amiga_1000_030 "AMIGA-1000/030"
#define Id_MN_Amiga_1200 "AMIGA-1200"
#define Id_MN_Amiga_1200_EC030 "AMIGA-1200/EC030"
#define Id_MN_Amiga_1200_030 "AMIGA-1200/030"
#define Id_MN_Amiga_1200_EC040 "AMIGA-1200/EC040"
#define Id_MN_Amiga_1200_LC040 "AMIGA-1200/LC040"
#define Id_MN_Amiga_1200_040 "AMIGA-1200/040"
#define Id_MN_Amiga_2000 "AMIGA-2000"
#define Id_MN_Amiga_2000_010 "AMIGA-2000/010"
#define Id_MN_Amiga_2000_020 "AMIGA-2000/020"
#define Id_MN_Amiga_2000_EC030 "AMIGA-2000/EC030"
#define Id_MN_Amiga_2000_030 "AMIGA-2000/030"
#define Id_MN_Amiga_2000_LC040 "AMIGA-2000/LC040"
#define Id_MN_Amiga_2000_EC040 "AMIGA-2000/EC040"
#define Id_MN_Amiga_2000_040 "AMIGA-2000/040"
#define Id_MN_Amiga_3000 "AMIGA-3000"
#define Id_MN_Amiga_3000_EC040 "AMIGA-3000/EC040"
#define Id_MN_Amiga_3000_LC040 "AMIGA-3000/LC040"
#define Id_MN_Amiga_3000_040 "AMIGA-3000/040"
#define Id_MN_Amiga_3000_060 "AMIGA-3000/060"
#define Id_MN_Amiga_4000_EC030 "AMIGA-4000/EC030"
#define Id_MN_Amiga_4000_030 "AMIGA-4000/030"
#define Id_MN_Amiga_4000_LC040 "AMIGA-4000/LC040"
#define Id_MN_Amiga_4000_040 "AMIGA-4000/040"
#define Id_MN_Amiga_4000_060 "AMIGA-4000/060"
#define Id_MN_Alto "ALTO"
#define Id_MN_Altos_6800 "ALTOS-6800"
#define Id_MN_Amdahl_V7 "AMDAHL-V7"
#define Id_MN_Apollo "APOLLO"
#define Id_MN_Apple_Macintosh "APPLE-MACINTOSH"
#define Id_MN_Apple_Powerbook "APPLE-POWERBOOK"
#define Id_MN_Atari_104ST "ATARI-104ST"
#define Id_MN_ATT_3B1 "ATT-3B1"
#define Id_MN_ATT_3B2 "ATT-3B2"
#define Id_MN_ATT_3B20 "ATT-3B20"
#define Id_MN_ATT_7300 "ATT-7300"
#define Id_MN_AXP "AXP"
#define Id_MN_BBN_C_60 "BBN-C/60"
#define Id_MN_Burroughs_B_29 "BURROUGHS-B/29"
#define Id_MN_Burroughs_B_4800 "BURROUGHS-B/4800"
#define Id_MN_Butterfly "BUTTERFLY"
#define Id_MN_C_30 "C/30"
#define Id_MN_C_70 "C/70"
#define Id_MN_Cadlinc "CADLINC"
#define Id_MN_CadR "CADR"
#define Id_MN_CDC_170 "CDC-170"
#define Id_MN_CDC_170_750 "CDC-170/750"
#define Id_MN_CDC_173 "CDC-173"
#define Id_MN_CDTV "CDTV"
#define Id_MN_CDTV_060 "CDTV/060"
#define Id_MN_CD32 "CD32"
#define Id_MN_Celerity_1200 "CELERITY-1200"
#define Id_MN_Club_386 "CLUB-386"
#define Id_MN_Compaq_386_20 "COMPAQ-386/20"
#define Id_MN_Comten_3690 "COMTEN-3690"
#define Id_MN_CP8040 "CP8040"
#define Id_MN_Cray_1 "CRAY-1"
#define Id_MN_Cray_X_MP "CRAY-X/MP"
#define Id_MN_Cray_2 "CRAY-2"
#define Id_MN_CTIWS_117 "CTIWS-117"
#define Id_MN_Dandelion "DANDELION"
#define Id_MN_DEC_10 "DEC-10"
#define Id_MN_DEC_1050 "DEC-1050"
#define Id_MN_DEC_1077 "DEC-1077"
#define Id_MN_DEC_1080 "DEC-1080"
#define Id_MN_DEC_1090 "DEC-1090"
#define Id_MN_DEC_1090B "DEC-1090B"
#define Id_MN_DEC_1090T "DEC-1090T"
#define Id_MN_DEC_2020T "DEC-2020T"
#define Id_MN_DEC_2040 "DEC-2040"
#define Id_MN_DEC_2040T "DEC-2040T"
#define Id_MN_DEC_2050T "DEC-2050T"
#define Id_MN_DEC_2060 "DEC-2060"
#define Id_MN_DEC_2060T "DEC-2060T"
#define Id_MN_DEC_2065 "DEC-2065"
#define Id_MN_DEC_AXP "DEC-AXP"
#define Id_MN_DEC_Falcon "DEC-FALCON"
#define Id_MN_DEC_KS10 "DEC-KS10"
#define Id_MN_DECStation "DECSTATION"
#define Id_MN_DEC_VAX "DEC-VAX"
#define Id_MN_DEC_VAXCluster "DEC-VAXCLUSTER"
#define Id_MN_DEC_VAXStation "DEC-VAXSTATION"
#define Id_MN_DEC_VAX_11730 "DEC-VAX-11730"
#define Id_MN_Dorado "DORADO"
#define Id_MN_DPS8_70M "DPS8/70M"
#define Id_MN_Elxsi_6400 "ELXSI-6400"
#define Id_MN_EverEx_386 "EVEREX-386"
#define Id_MN_Foonly_F2 "FOONLY-F2"
#define Id_MN_Foonly_F3 "FOONLY-F3"
#define Id_MN_Foonly_F4 "FOONLY-F4"
#define Id_MN_Gould "GOULD"
#define Id_MN_Gould_6050 "GOULD-6050"
#define Id_MN_Gould_6080 "GOULD-6080"
#define Id_MN_Gould_9050 "GOULD-9050"
#define Id_MN_Gould_9080 "GOULD-9080"
#define Id_MN_H_316 "H-316"
#define Id_MN_H_60_68 "H-60/68"
#define Id_MN_H_68 "H-68"
#define Id_MN_H_68_80 "H-68/80"
#define Id_MN_H_89 "H-89"
#define Id_MN_Honeywell_DPS_6 "HONEYWELL-DPS-6"
#define Id_MN_Honeywell_BPS_8_70 "HONEYWELL-DPS-8/70"
#define Id_MN_HP3000 "HP3000"
#define Id_MN_HP3000_64 "HP3000/64"
#define Id_MN_IBM_158 "IBM-158"
#define Id_MN_IBM_360_67 "IBM-360/67"
#define Id_MN_IBM_370_3033 "IBM-370/3033"
#define Id_MN_IBM_3081 "IBM-3081"
#define Id_MN_IBM_3084QX "IBM-3084QX"
#define Id_MN_IBM_3101 "IBM-3101"
#define Id_MN_IBM_4331 "IBM-4331"
#define Id_MN_IBM_4341 "IBM-4341"
#define Id_MN_IBM_4361 "IBM-4361"
#define Id_MN_IBM_4381 "IBM-4381"
#define Id_MN_IBM_4956 "IBM-4956"
#define Id_MN_IBM_6152 "IBM-6152"
#define Id_MN_IBM_PC "IBM-PC"
#define Id_MN_IBM_PC_AT "IBM-PC/AT"
#define Id_MN_IBM_PC_RT "IBM-PC/RT"
#define Id_MN_IBM_PC_XT "IBM-PC/XT"
#define Id_MN_IBM_RS_6000 "IBM-RS/6000"
#define Id_MN_IBM_Series_1 "IBM-SERIES/1"
#define Id_MN_Imagen "IMAGEN"
#define Id_MN_Imagen_8_300 "IMAGEN-8/300"
#define Id_MN_Imsai "IMSAI"
#define Id_MN_Integrated_Solutions "INTEGRATED-SOLUTIONS"
#define Id_MN_Integrated_Solutions_68K "INTEGRATED-SOLUTIONS-68K"
#define Id_MN_Integrated_Solutions_Creator "INTEGRATED-SOLUTIONS-CREATOR"
#define Id_MN_Integrated_Solutions_Creator_8 "INTEGRATED-SOLUTIONS-CREATOR-8"
#define Id_MN_INTEL_386 "INTEL-386"
#define Id_MN_INTEL_IPSC "INTEL-IPSC"
#define Id_MN_Is_1 "IS-1"
#define Id_MN_Is_68010 "IS-68010"
#define Id_MN_LMI "LMI"
#define Id_MN_LSI_11 "LSI-11"
#define Id_MN_LSI_11_2 "LSI-11/2"
#define Id_MN_LSI_11_23 "LSI-11/23"
#define Id_MN_LSI_11_73 "LSI-11/73"
#define Id_MN_M68000 "M68000"
#define Id_MN_Mac_II "MAC-II"
#define Id_MN_Mac_Powerbook "MAC-POWERBOOK"
#define Id_MN_MacIntosh "MACINTOSH"
#define Id_MN_MassComp "MASSCOMP"
#define Id_MN_MC500 "MC500"
#define Id_MN_68000 "MC68000"
#define Id_MN_Microport "MICROPORT"
#define Id_MN_MicroVAX "MICROVAX"
#define Id_MN_MicroVAX_I "MICROVAX-I"
#define Id_MN_MV_8000 "MV/8000"
#define Id_MN_NAS3_5 "NAS3-5"
#define Id_MN_NCR_Comten_3690 "NCR-COMTEN-3690"
#define Id_MN_Next_N1000_316 "NEXT/N1000-316"
#define Id_MN_Now "NOW"
#define Id_MN_Onyx_Z8000 "ONYX-Z8000"
#define Id_MN_PDP_11 "PDP-11"
#define Id_MN_PDP_11_3 "PDP-11/3"
#define Id_MN_PDP_11_23 "PDP-11/23"
#define Id_MN_PDP_11_24 "PDP-11/24"
#define Id_MN_PDP_11_34 "PDP-11/34"
#define Id_MN_PDP_11_40 "PDP-11/40"
#define Id_MN_PDP_11_44 "PDP-11/44"
#define Id_MN_PDP_11_45 "PDP-11/45"
#define Id_MN_PDP_11_50 "PDP-11/50"
#define Id_MN_PDP_11_70 "PDP-11/70"
#define Id_MN_PDP_11_73 "PDP-11/73"
#define Id_MN_PE_7_32 "PE-7/32"
#define Id_MN_PE_3205 "PE-3205"
#define Id_MN_PE_Perq "PERQ"
#define Id_MN_Plexus_P_60 "PLEXUS-P/60"
#define Id_MN_PLI "PLI"
#define Id_MN_Pluribus "PLURIBUS"
#define Id_MN_Prime_2350 "PRIME-2350"
#define Id_MN_Prime_2450 "PRIME-2450"
#define Id_MN_Prime_2755 "PRIME-2755"
#define Id_MN_Prime_9655 "PRIME-9655"
#define Id_MN_Prime_9755 "PRIME-9755"
#define Id_MN_Prime_9955II "PRIME-9955II"
#define Id_MN_Prime_2250 "PRIME-2250"
#define Id_MN_Prime_2655 "PRIME-2655"
#define Id_MN_Prime_9955 "PRIME-9955"
#define Id_MN_Prime_9950 "PRIME-9950"
#define Id_MN_Prime_9650 "PRIME-9650"
#define Id_MN_Prime_9750 "PRIME-9750"
#define Id_MN_Prime_750 "PRIME-750"
#define Id_MN_Prime_850 "PRIME-850"
#define Id_MN_Prime_550II "PRIME-550II"
#define Id_MN_Pyramid_90 "PYRAMID-90"
#define Id_MN_Pyramid_90MX "PYRAMID-90MX"
#define Id_MN_Pyramid_90X "PYRAMID-90X"
#define Id_MN_Ridge "RIDGE"
#define Id_MN_Ridge_32 "RIDGE-32"
#define Id_MN_Ridge_32C "RIDGE-32C"
#define Id_MN_ROLM_1666 "ROLM-1666"
#define Id_MN_RS_6000 "RS/6000"
#define Id_MN_S1_MKIIA "S1-MKIIA"
#define Id_MN_SMI "SMI"
#define Id_MN_Sequent_Balance_8000 "SEQUENT-BALANCE-8000"
#define Id_MN_Emens "SIEMENS"
#define Id_MN_Silicon_Graphics "SILICON-GRAPHICS"
#define Id_MN_Silicon_Graphics_Iris "SILICON-GRAPHICS-IRIS"
#define Id_MN_SGI_Iris_2400 "SGI-IRIS-2400"
#define Id_MN_SGI_Iris_2500 "SGI-IRIS-2500"
#define Id_MN_SGI_Iris_3010 "SGI-IRIS-3010"
#define Id_MN_SGI_Iris_3020 "SGI-IRIS-3020"
#define Id_MN_SGI_Iris_3030 "SGI-IRIS-3030"
#define Id_MN_SGI_Iris_3110 "SGI-IRIS-3110"
#define Id_MN_SGI_Iris_3115 "SGI-IRIS-3115"
#define Id_MN_SGI_Iris_3120 "SGI-IRIS-3120"
#define Id_MN_SGI_Iris_3130 "SGI-IRIS-3130"
#define Id_MN_SGI_Iris_4D_20 "SGI-IRIS-4D/20"
#define Id_MN_SGI_Iris_4D_20G "SGI-IRIS-4D/20G"
#define Id_MN_SGI_Iris_4D_25 "SGI-IRIS-4D/25"
#define Id_MN_SGI_Iris_4D_25G "SGI-IRIS-4D/25G"
#define Id_MN_SGI_Iris_4D_25S "SGI-IRIS-4D/25S"
#define Id_MN_SGI_Iris_4D_50 "SGI-IRIS-4D/50"
#define Id_MN_SGI_Iris_4D_50G "SGI-IRIS-4D/50G"
#define Id_MN_SGI_Iris_4D_50GT "SGI-IRIS-4D/50GT"
#define Id_MN_SGI_Iris_4D_60 "SGI-IRIS-4D/60"
#define Id_MN_SGI_Iris_4D_60G "SGI-IRIS-4D/60G"
#define Id_MN_SGI_Iris_4D_60T "SGI-IRIS-4D/60T"
#define Id_MN_SGI_Iris_4D_60GT "SGI-IRIS-4D/60GT"
#define Id_MN_SGI_Iris_4D_70 "SGI-IRIS-4D/70"
#define Id_MN_SGI_Iris_4D_70G "SGI-IRIS-4D/70G"
#define Id_MN_SGI_Iris_4D_70GT "SGI-IRIS-4D/70GT"
#define Id_MN_SGI_Iris_4D_80GT "SGI-IRIS-4D/80GT"
#define Id_MN_SGI_Iris_4D_80S "SGI-IRIS-4D/80S"
#define Id_MN_SGI_Iris_4D_120GTX "SGI-IRIS-4D/120GTX"
#define Id_MN_SGI_Iris_4D_120S "SGI-IRIS-4D/120S"
#define Id_MN_SGI_Iris_4D_210GTX "SGI-IRIS-4D/210GTX"
#define Id_MN_SGI_Iris_4D_210S "SGI-IRIS-4D/210S"
#define Id_MN_SGI_Iris_4D_220GTX "SGI-IRIS-4D/220GTX"
#define Id_MN_SGI_Iris_4D_220S "SGI-IRIS-4D/220S"
#define Id_MN_SGI_Iris_4D_240GTX "SGI-IRIS-4D/240GTX"
#define Id_MN_SGI_Iris_4D_240S "SGI-IRIS-4D/240S"
#define Id_MN_SGI_Iris_4D_280GTX "SGI-IRIS-4D/280GTX"
#define Id_MN_SGI_Iris_4D_280S "SGI-IRIS-4D/280S"
#define Id_MN_SGI_Iris_CS_12 "SGI-IRIS-CS/12"
#define Id_MN_SGI_Iris_4Server_8 "SGI-IRIS-4SERVER-8"
#define Id_MN_Sperry_DCP_10 "SPERRY-DCP/10"
#define Id_MN_Sun "SUN"
#define Id_MN_Sun_2 "SUN-2"
#define Id_MN_Sun_2_50 "SUN-2/50"
#define Id_MN_Sun_2_100 "SUN-2/100"
#define Id_MN_Sun_2_120 "SUN-2/120"
#define Id_MN_Sun_2_130 "SUN-2/130"
#define Id_MN_Sun_2_140 "SUN-2/140"
#define Id_MN_Sun_2_150 "SUN-2/150"
#define Id_MN_Sun_2_160 "SUN-2/160"
#define Id_MN_Sun_2_170 "SUN-2/170"
#define Id_MN_Sun_3_50 "SUN-3/50"
#define Id_MN_Sun_3_60 "SUN-3/60"
#define Id_MN_Sun_3_75 "SUN-3/75"
#define Id_MN_Sun_3_80 "SUN-3/80"
#define Id_MN_Sun_3_110 "SUN-3/110"
#define Id_MN_Sun_3_140 "SUN-3/140"
#define Id_MN_Sun_3_150 "SUN-3/150"
#define Id_MN_Sun_3_160 "SUN-3/160"
#define Id_MN_Sun_3_180 "SUN-3/180"
#define Id_MN_Sun_3_200 "SUN-3/200"
#define Id_MN_Sun_3_260 "SUN-3/260"
#define Id_MN_Sun_3_280 "SUN-3/280"
#define Id_MN_Sun_3_470 "SUN-3/470"
#define Id_MN_Sun_3_480 "SUN-3/480"
#define Id_MN_Sun_4_60 "SUN-4/60"
#define Id_MN_Sun_4_110 "SUN-4/110"
#define Id_MN_Sun_4_150 "SUN-4/150"
#define Id_MN_Sun_4_200 "SUN-4/200"
#define Id_MN_Sun_4_260 "SUN-4/260"
#define Id_MN_Sun_4_280 "SUN-4/280"
#define Id_MN_Sun_4_330 "SUN-4/330"
#define Id_MN_Sun_4_370 "SUN-4/370"
#define Id_MN_Sun_4_390 "SUN-4/390"
#define Id_MN_Sun_50 "SUN-50"
#define Id_MN_Sun_100 "SUN-100"
#define Id_MN_Sun_120 "SUN-120"
#define Id_MN_Sun_130 "SUN-130"
#define Id_MN_Sun_150 "SUN-150"
#define Id_MN_Sun_170 "SUN-170"
#define Id_MN_Sun_386i_250 "SUN-386i/250"
#define Id_MN_Sun_68000 "SUN-68000"
#define Id_MN_Symbolics_3600 "SYMBOLICS-3600"
#define Id_MN_Symbolics_3670 "SYMBOLICS-3670"
#define Id_MN_Symmetric_375 "SYMMETRIC-375"
#define Id_MN_Symult "SYMULT"
#define Id_MN_Tandem_TXP "TANDEM-TXP"
#define Id_MN_Tandy_6000 "TANDY-6000"
#define Id_MN_Tek_6130 "TEK-6130"
#define Id_MN_TI_Explorer "TI-EXPLORER"
#define Id_MN_TP_4000 "TP-4000"
#define Id_MN_TRS_80 "TRS-80"
#define Id_MN_Univac_1100 "UNIVAC-1100"
#define Id_MN_Univac_1100_60 "UNIVAC-1100/60"
#define Id_MN_Univac_1100_62 "UNIVAC-1100/62"
#define Id_MN_Univac_1100_63 "UNIVAC-1100/63"
#define Id_MN_Univac_1100_64 "UNIVAC-1100/64"
#define Id_MN_Univac_1100_70 "UNIVAC-1100/70"
#define Id_MN_Univac_1160 "UNIVAC-1160"
#define Id_MN_Unknown "UNKNOWN"
#define Id_MN_VAX "VAX"
#define Id_MN_VAX_11_725 "VAX-11/725"
#define Id_MN_VAX_11_730 "VAX-11/730"
#define Id_MN_VAX_11_750 "VAX-11/750"
#define Id_MN_VAX_11_780 "VAX-11/780"
#define Id_MN_VAX_11_785 "VAX-11/785"
#define Id_MN_VAX_11_790 "VAX-11/790"
#define Id_MN_VAX_11_8600 "VAX-11/8600"
#define Id_MN_VAX_8600 "VAX-8600"
#define Id_MN_VAXCluster "VAXCLUSTER"
#define Id_MN_VAXStation "VAXSTATION"
#define Id_MN_Wang_PC002 "WANG-PC002"
#define Id_MN_Wang_VS100 "WANG-VS100"
#define Id_MN_Wang_VS400 "WANG-VS400"
#define Id_MN_Wyse_386 "WYSE-386"
#define Id_MN_Wyse_WN5004 "WYSE-WN5004"
#define Id_MN_Wyse_WN5008 "WYSE-WN5008"
#define Id_MN_Wyse_WN5104 "WYSE-WN5104"
#define Id_MN_Wyse_WN5108 "WYSE-WN5108"
#define Id_MN_Wyse_WX15C "WYSE-WX15C"
#define Id_MN_Wyse_WX17C "WYSE-WX17C"
#define Id_MN_Wyse_WX17M "WYSE-WX17M"
#define Id_MN_Wyse_WX19C "WYSE-WX19C"
#define Id_MN_Wyse_WX19M "WYSE-WX19M"
#define Id_MN_Wyse_WYX14M "WYSE-WYX14M"
#define Id_MN_Wyse_WYX5 "WYSE-WYX5"
#define Id_MN_Xerox_1108 "XEROX-1108"
#define Id_MN_Xerox_8010 "XEROX-8010"
#define Id_MN_Zenith_148 "ZENITH-148"
#define Id_CS_US_ASCII "US-ASCII"
#define Id_CS_ISO_8859_1 "ISO-8859-1"
#define Id_CS_ISO_8859_2 "ISO-8859-2"
#define Id_CS_ISO_8859_3 "ISO-8859-3"
#define Id_CS_ISO_8859_4 "ISO-8859-4"
#define Id_CS_ISO_8859_5 "ISO-8859-5"
#define Id_CS_ISO_8859_6 "ISO-8859-6"
#define Id_CS_ISO_8859_7 "ISO-8859-7"
#define Id_CS_ISO_8859_8 "ISO-8859-8"
#define Id_CS_ISO_8859_9 "ISO-8859-9"
#define Id_IPMC_Base_Address "224.0.0.0"
#define Id_IPMC_All_Systems "224.0.0.1"
#define Id_IPMC_All_Routers "224.0.0.2"
#define Id_IPMC_Unassigned "224.0.0.3"
#define Id_IPMC_DVMRP_Routers "224.0.0.4"
#define Id_IPMC_OSPFIGP_All_Routers "224.0.0.5"
#define Id_IPMC_OSPFIGP_Designated_Routers "224.0.0.6"
#define Id_IPMC_ST_Routers "224.0.0.7"
#define Id_IPMC_ST_Hosts "224.0.0.8"
#define Id_IPMC_RIP2_Routers "224.0.0.9"
#define Id_IPMC_IGRP_Routers "224.0.0.10"
#define Id_IPMC_Mobile_Agents "224.0.0.11"
#define Id_IPMC_DHCP_Server "224.0.0.12"
#define Id_IPMC_DHCP_All_PIM_Routers "224.0.0.13"
#define Id_IPMC_RSVP_ENCAPSULATION "224.0.0.14"
#define Id_IPMC_All_CDT_Routers "224.0.0.15"
#define Id_IPMC_designated_sbm "224.0.0.16"
#define Id_IPMC_ll_sbms "224.0.0.17"
#define Id_IPMC_VRRP "224.0.0.18"
#define Id_IPMC_IPAllL1ISs "224.0.0.19"
#define Id_IPMC_IPAllL2ISs "224.0.0.20"
#define Id_IPMC_IPAllIntermediate_Systems "224.0.0.21"
#define Id_IPMC_IGMP "224.0.0.22"
#define Id_IPMC_GLOBECAST_ID "224.0.0.23"
#define Id_IPMC_router_to_switch "224.0.0.25"
#define Id_IPMC_Al_MPP_Hello "224.0.0.27"
#define Id_IPMC_ETC_Control "224.0.0.28"
#define Id_IPMC_GE_FANUC "224.0.0.29"
#define Id_IPMC_INDIGO_VHDP "224.0.0.30"
#define Id_IPMC_shinbroadband "224.0.0.31"
#define Id_IPMC_digistar "224.0.0.32"
#define Id_IPMC_ff_system_Management "224.0.0.33"
#define Id_IPMC_pt2_discover "224.0.0.34"
#define Id_IPMC_DXCLUSTER "224.0.0.35"
#define Id_IPMC_DTC_Announcement "224.0.0.36"
#define Id_IPMC_zeroconfaddr_Min "224.0.0.37"
#define Id_IPMC_zeroconfaddr_Max "224.0.0.68"
#define Id_IPMC_Cisco_nhap "224.0.0.101"
#define Id_IPMC_HSPP "224.0.0.102"
#define Id_IPMC_MDAP "224.0.0.103"
#define Id_IPMC_mDNS "224.0.0.251"
#define Id_IPMC_VMTP_Managers "224.0.1.0"
#define Id_IPMC_NTP_Protocol "224.0.1.1"
#define Id_IPMC_SGI_Dogfight "224.0.1.2"
#define Id_IPMC_Rwhod "224.0.1.3"
#define Id_IPMC_VNP "224.0.1.4"
#define Id_IPMC_Artificial_Horizons "224.0.1.5"
#define Id_IPMC_NSS "224.0.1.6"
#define Id_IPMC_AUDIONEWS "224.0.1.7"
#define Id_IPMC_SUN_NIS_Plus_Information "224.0.1.8"
#define Id_IPMC_MTP_Protocol "224.0.1.9"
#define Id_IPMC_IETF_1_Low_Audio "224.0.1.10"
#define Id_IPMC_IETF_1_Audio "224.0.1.11"
#define Id_IPMC_IETF_1_Video "224.0.1.12"
#define Id_IPMC_IETF_2_Low_Audio "224.0.1.13"
#define Id_IPMC_IETF_2_Audio "224.0.1.14"
#define Id_IPMC_IETF_2_Video "224.0.1.15"
#define Id_IPMC_Music_Service "224.0.1.16"
#define Id_IPMC_SEANET_TELEMETRY "224.0.1.17"
#define Id_IPMC_SEANET_IMAGE "224.0.1.18"
#define Id_IPMC_MLOADD "224.0.1.19"
#define Id_IPMC_Private_Expiriment "224.0.1.20"
#define Id_IPMC_DVMRP_on_MOSPF "224.0.1.21"
#define Id_IPMC_SVRLOC "224.0.1.22"
#define Id_IPMC_XINGTV "224.0.1.23"
#define Id_IPMC_Microsoft_DS "224.0.1.24"
#define Id_IPMC_NBC_Pro "224.0.1.25"
#define Id_IPMC_NBC_Pfn "224.0.1.26"
#define Id_IPMC_lmsc_calren_1 "224.0.1.27"
#define Id_IPMC_lmsc_calren_2 "224.0.1.28"
#define Id_IPMC_lmsc_calren_3 "224.0.1.29"
#define Id_IPMC_lmsc_calren_4 "224.0.1.30"
#define Id_IPMC_ampr_info "224.0.1.31"
#define Id_IPMC_mtrace "224.0.1.32"
#define Id_IPMC_RSVP_encap_1 "224.0.1.33"
#define Id_IPMC_RSVP_encap_2 "224.0.1.34"
#define Id_IPMC_SVRLOC_DA "224.0.1.35"
#define Id_IPMC_rln_server "224.0.1.36"
#define Id_IPMC_proshare_mc "224.0.1.37"
#define Id_IPMC_dantz "224.0.1.38"
#define Id_IPMC_cisco_rp_announce "224.0.1.39"
#define Id_IPMC_cisco_rp_discovery "224.0.1.40"
#define Id_IPMC_gatekeeper "224.0.1.41"
#define Id_IPMC_iberiagames "224.0.1.42"
#define Id_IPMC_nwn_discovery "224.0.1.43"
#define Id_IPMC_nwn_adaptor "224.0.1.44"
#define Id_IPMC_isma_1 "224.0.1.45"
#define Id_IPMC_isma_2 "224.0.1.46"
#define Id_IPMC_telerate "224.0.1.47"
#define Id_IPMC_ciena "224.0.1.48"
#define Id_IPMC_dcap_servers "224.0.1.49"
#define Id_IPMC_dcap_clients "224.0.1.50"
#define Id_IPMC_mcntp_directory "224.0.1.51"
#define Id_IPMC_mbone_vcr_directory "224.0.1.52"
#define Id_IPMC_heartbeat "224.0.1.53"
#define Id_IPMC_sun_mc_grp "224.0.1.54"
#define Id_IPMC_extended_sys "224.0.1.55"
#define Id_IPMC_pdrncs "224.0.1.56"
#define Id_IPMC_tns_adv_multi "224.0.1.57"
#define Id_IPMC_vcals_dmu "224.0.1.58"
#define Id_IPMC_zuba "224.0.1.59"
#define Id_IPMC_hp_device_disc "224.0.1.60"
#define Id_IPMC_tms_production "224.0.1.61"
#define Id_IPMC_sunscalar "224.0.1.62"
#define Id_IPMC_mmtp_poll "224.0.1.63"
#define Id_IPMC_compaq_peer "224.0.1.64"
#define Id_IPMC_iapp "224.0.1.65"
#define Id_IPMC_multihasc_com "224.0.1.66"
#define Id_IPMC_serv_discovery "224.0.1.67"
#define Id_IPMC_mdhcpdisover "224.0.1.68"
#define Id_IPMC_MMP_bundle_discovery1 "224.0.1.69"
#define Id_IPMC_MMP_bundle_discovery2 "224.0.1.70"
#define Id_IPMC_XYPOINT "224.0.1.71"
#define Id_IPMC_GilatSkySurfer "224.0.1.72"
#define Id_IPMC_SharesLive "224.0.1.73"
#define Id_IPMC_NorthernData "224.0.1.74"
#define Id_IPMC_SIP "224.0.1.75"
#define Id_IPMC_IAPP2 "224.0.1.76"
#define Id_IPMC_AGENTVIEW "224.0.1.77"
#define Id_IPMC_Tibco_1 "224.0.1.78"
#define Id_IPMC_Tibco_2 "224.0.1.79"
#define Id_IPMC_MSP "224.0.1.80"
#define Id_IPMC_OTT "224.0.1.81"
#define Id_IPMC_TRACKTICKER "224.0.1.82"
#define Id_IPMC_dtn_mc "224.0.1.83"
#define Id_IPMC_jini_announcement "224.0.1.84"
#define Id_IPMC_jini_request "224.0.1.85"
#define Id_IPMC_sde_discovery "224.0.1.86"
#define Id_IPMC_DirecPC_SI "224.0.1.87"
#define Id_IPMC_B1RMonitor "224.0.1.88"
#define Id_IPMC_3Com_AMP3 "224.0.1.89"
#define Id_IPMC_imFtmSvc "224.0.1.90"
#define Id_IPMC_NQDS4 "224.0.1.91"
#define Id_IPMC_NQDS5 "224.0.1.92"
#define Id_IPMC_NQDS6 "224.0.1.93"
#define Id_IPMC_NLVL12 "224.0.1.94"
#define Id_IPMC_NTDS1 "224.0.1.95"
#define Id_IPMC_NTDS2 "224.0.1.96"
#define Id_IPMC_NODSA "224.0.1.97"
#define Id_IPMC_NODSB "224.0.1.98"
#define Id_IPMC_NODSC "224.0.1.99"
#define Id_IPMC_NODSD "224.0.1.100"
#define Id_IPMC_NQDS4R "224.0.1.101"
#define Id_IPMC_NQDS5R "224.0.1.102"
#define Id_IPMC_NQDS6R "224.0.1.103"
#define Id_IPMC_NLVL12R "224.0.1.104"
#define Id_IPMC_NTDS1R "224.0.1.105"
#define Id_IPMC_NTDS2R "224.0.1.106"
#define Id_IPMC_NODSAR "224.0.1.107"
#define Id_IPMC_NODSBR "224.0.1.108"
#define Id_IPMC_NODSCR "224.0.1.109"
#define Id_IPMC_NODSDR "224.0.1.110"
#define Id_IPMC_MRM "224.0.1.111"
#define Id_IPMC_TVE_FILE "224.0.1.112"
#define Id_IPMC_TVE_ANNOUNCE "224.0.1.113"
#define Id_IPMC_Mac "224.0.1.114"
#define Id_IPMC_Simple "224.0.1.115"
#define Id_IPMC_SpectraLinkGW "224.0.1.116"
#define Id_IPMC_dieboldmcast "224.0.1.117"
#define Id_IPMC_Tivoli "224.0.1.118"
#define Id_IPMC_pq_lic_mcast "224.0.1.119"
#define Id_IPMC_HYPERFEED "224.0.1.120"
#define Id_IPMC_Pipesplatform "224.0.1.121"
#define Id_IPMC_LiebDevMgmg_DM "224.0.1.122"
#define Id_IPMC_TRIBALVOICE "224.0.1.123"
#define Id_IPMC_PolyCom "224.0.1.125"
#define Id_IPMC_Infront "224.0.1.126"
#define Id_IPMC_XRX "224.0.1.127"
#define Id_IPMC_CNN "224.0.1.128"
#define Id_IPMC_PTP_primary "224.0.1.129"
#define Id_IPMC_PTP_alternate1 "224.0.1.130"
#define Id_IPMC_PTP_alternate2 "224.0.1.131"
#define Id_IPMC_PTP_alternate3 "224.0.1.132"
#define Id_IPMC_ProCast "224.0.1.133"
#define Id_IPMC_3Com "224.0.1.134"
#define Id_IPMC_CS_Multicasting "224.0.1.135"
#define Id_IPMC_TS_MC_1 "224.0.1.136"
#define Id_IPMC_Make "224.0.1.137"
#define Id_IPMC_Teleborsa "224.0.1.138"
#define Id_IPMC_SUMAConfig "224.0.1.139"
#define Id_IPMC_DHCP_SERVERS "224.0.1.141"
#define Id_IPMC_CN "224.0.1.142"
#define Id_IPMC_EMWIN "224.0.1.143"
#define Id_IPMC_Alchemy "224.0.1.144"
#define Id_IPMC_Satcast_1 "224.0.1.145"
#define Id_IPMC_Satcast_2 "224.0.1.146"
#define Id_IPMC_Satcast_3 "224.0.1.147"
#define Id_IPMC_Intline "224.0.1.148"
#define Id_IPMC_8x8 "224.0.1.149"
#define Id_IPMC_Intline_1 "224.0.1.151"
#define Id_IPMC_Intline_2 "224.0.1.152"
#define Id_IPMC_Intline_3 "224.0.1.153"
#define Id_IPMC_Intline_4 "224.0.1.154"
#define Id_IPMC_Intline_5 "224.0.1.155"
#define Id_IPMC_Intline_6 "224.0.1.156"
#define Id_IPMC_Intline_7 "224.0.1.157"
#define Id_IPMC_Intline_8 "224.0.1.158"
#define Id_IPMC_Intline_9 "224.0.1.159"
#define Id_IPMC_Intline_10 "224.0.1.160"
#define Id_IPMC_Intline_11 "224.0.1.161"
#define Id_IPMC_Intline_12 "224.0.1.162"
#define Id_IPMC_Intline_13 "224.0.1.163"
#define Id_IPMC_Intline_14 "224.0.1.164"
#define Id_IPMC_Intline_15 "224.0.1.165"
#define Id_IPMC_marratech_cc "224.0.1.166"
#define Id_IPMC_EMS_InterDev "224.0.1.167"
#define Id_IPMC_itb301 "224.0.1.168"
#define Id_IPMC_rtv_audio "224.0.1.169"
#define Id_IPMC_rtv_video "224.0.1.170"
#define Id_IPMC_HAVI_Sim "224.0.1.171"
#define Id_IPMC_Nokia "224.0.1.172"
#define Id_IPMC_host_request "224.0.1.173"
#define Id_IPMC_host_announce "224.0.1.174"
#define Id_IPMC_ptk_cluster "224.0.1.175"
#define Id_IPMC_Proxim "224.0.1.176"
#define Id_IPMC_rwho "224.0.2.1"
#define Id_IPMC_SUN "224.0.2.2"
#define Id_IPMC_SIAC_Min "224.0.2.064"
#define Id_IPMC_SIAC_Max "224.0.2.095"
#define Id_IPMC_CoolCast_Min "224.0.2.096"
#define Id_IPMC_CoolCast_Max "224.0.2.127"
#define Id_IPMC_WOZ_Garage_Min "224.0.2.128"
#define Id_IPMC_WOZ_Garage_Max "224.0.2.191"
#define Id_IPMC_SIAC_Market_Min "224.0.2.192"
#define Id_IPMC_SIAC_Market_Max "224.0.2.255"
#define Id_IPMC_RFE_Generic_Min "224.0.3.000"
#define Id_IPMC_RFE_Generic_Max "224.0.3.255"
#define Id_IPMC_RFE_Individual_Min "224.0.4.000"
#define Id_IPMC_RFE_Individual_Max "224.0.4.255"
#define Id_IPMC_CDPD_Min "224.0.5.000"
#define Id_IPMC_CDPD_Max "224.0.5.127"
#define Id_IPMC_SIAC_Market2_Min "224.0.5.128"
#define Id_IPMC_SIAC_Market2_Max "224.0.5.191"
#define Id_IPMC_SIAC_MYSE_Min "224.0.5.192"
#define Id_IPMC_SIAC_MYSE_Max "224.0.5.255"
#define Id_IPMC_Cornell_Min "224.0.6.000"
#define Id_IPMC_Cornell_Max "224.0.6.127"
#define Id_IPMC_Where_Are_You_Min "224.0.7.000"
#define Id_IPMC_Where_Are_You_Max "224.0.7.255"
#define Id_IPMC_INTV_Min "224.0.8.000"
#define Id_IPMC_INTV_Max "224.0.8.255"
#define Id_IPMC_Invisible_Min "224.0.9.000"
#define Id_IPMC_Invisible_Max "224.0.9.255"
#define Id_IPMC_DLSw_Min "224.0.10.000"
#define Id_IPMC_DLSw_Max "224.0.10.255"
#define Id_IPMC_NCC_NET_Min "224.0.11.000"
#define Id_IPMC_NCC_NET_Max "224.0.11.255"
#define Id_IPMC_Microsoft_Min "224.0.12.000"
#define Id_IPMC_Microsoft_Max "224.0.12.063"
#define Id_IPMC_UUNET_Min "224.0.13.000"
#define Id_IPMC_UUNET_Max "224.0.13.255"
#define Id_IPMC_NLANR_Min "224.0.14.000"
#define Id_IPMC_NLANR_Max "224.0.14.255"
#define Id_IPMC_Hewlett_Min "224.0.15.000"
#define Id_IPMC_Hewlett_Max "224.0.15.255"
#define Id_IPMC_XingNet_Min "224.0.16.000"
#define Id_IPMC_XingNet_Max "224.0.16.255"
#define Id_IPMC_Mercantile_Min "224.0.17.000"
#define Id_IPMC_Mercantile_Max "224.0.17.031"
#define Id_IPMC_NDQMD1_Min "224.0.17.032"
#define Id_IPMC_NDQMD1_Max "224.0.17.063"
#define Id_IPMC_ODN_DTV_Min "224.0.17.064"
#define Id_IPMC_ODN_DTV_Max "224.0.17.127"
#define Id_IPMC_Dow_Min "224.0.18.000"
#define Id_IPMC_Dow_Max "224.0.18.255"
#define Id_IPMC_Walt_Min "224.0.19.000"
#define Id_IPMC_Walt_Max "224.0.19.063"
#define Id_IPMC_Cal_Min "224.0.19.064"
#define Id_IPMC_Cal_Max "224.0.19.095"
#define Id_IPMC_SIAC_Market3_Min "224.0.19.096"
#define Id_IPMC_SIAC_Market3_Max "224.0.19.127"
#define Id_IPMC_IIG_Min "224.0.19.128"
#define Id_IPMC_IIG_Max "224.0.19.191"
#define Id_IPMC_Metropol_Min "224.0.19.192"
#define Id_IPMC_Metropol_Max "224.0.19.207"
#define Id_IPMC_Xenoscience_Min "224.0.19.208"
#define Id_IPMC_Xenoscience_Max "224.0.19.239"
#define Id_IPMC_HYPERFEED_Min "224.0.19.240"
#define Id_IPMC_HYPERFEED_Max "224.0.19.255"
#define Id_IPMC_MS_IP_TV_Min "224.0.20.000"
#define Id_IPMC_MS_IP_TV_Max "224.0.20.063"
#define Id_IPMC_Reliable_Min "224.0.20.064"
#define Id_IPMC_Reliable_Max "224.0.20.127"
#define Id_IPMC_TRACKTICKER_Min "224.0.20.128"
#define Id_IPMC_TRACKTICKER_Max "224.0.20.143"
#define Id_IPMC_CNR_Min "224.0.20.144"
#define Id_IPMC_CNR_Max "224.0.20.207"
#define Id_IPMC_Talarian_Min "224.0.21.000"
#define Id_IPMC_Talarian_Max "224.0.21.127"
#define Id_IPMC_WORLD_Min "224.0.22.000"
#define Id_IPMC_WORLD_Max "224.0.22.255"
#define Id_IPMC_Domain_Min "224.0.252.000"
#define Id_IPMC_Domain_Max "224.0.252.000-224.0.252.255"
#define Id_IPMC_Report_Min "224.0.253.000"
#define Id_IPMC_Report_Max "224.0.253.255"
#define Id_IPMC_Query_Min "224.0.254.000"
#define Id_IPMC_Query_Max "224.0.254.255"
#define Id_IPMC_Border_Min "224.0.255.000"
#define Id_IPMC_Border_Max "224.0.255.255"
#define Id_IPMC_Multimedia_Min "224.2.0.0"
#define Id_IPMC_Multimedia_Max "224.2.127.253"
#define Id_IPMC_SAPv1 "224.2.127.254"
#define Id_IPMC_SAPv0 "224.2.127.255"
#define Id_IPMC_SAP_Min "224.2.128.0"
#define Id_IPMC_SAP_Max "224.2.255.255"
#define Id_IPMC_DIS_Min "224.252.000.000"
#define Id_IPMC_DIS_Max "224.255.255.255"
#define Id_IPMC_Source_Min "232.000.000.000"
#define Id_IPMC_Source_Max "232.255.255.255"
#define Id_IPMC_GLOP_Min "233.000.000.000"
#define Id_IPMC_GLOP_Max "233.255.255.255"
#define Id_IPMC_Administratively_Min "239.000.000.000"
#define Id_IPMC_Administratively_Max "239.255.255.255"
#define Id_IPMC_Organization_Local_Min "239.192.000.000"
#define Id_IPMC_Organization_Local_Max "239.251.255.255"
#define Id_IPMC_Site_Local1_Min "239.252.000.000"
#define Id_IPMC_Site_Local1_Max "239.252.255.255"
#define Id_IPMC_Site_Local2_Min "239.253.000.000"
#define Id_IPMC_Site_Local2_Max "239.253.255.255"
#define Id_IPMC_Site_Local3_Min "239.254.000.000"
#define Id_IPMC_Site_Local3_Max "239.254.255.255"
#define Id_IPMC_Site_Local4_Min "239.255.000.000"
#define Id_IPMC_Site_Local4_Max "239.255.255.255"
#define Id_IPMC_rasadv "239.255.002.002"
static const Shortint Id_IPREL_SAP = 0x0;
static const Shortint Id_IPREL_MADCAP = 0x1;
static const Shortint Id_IPREL_SLPV2 = 0x2;
static const Shortint Id_IPREL_MZAP = 0x3;
static const Shortint Id_IPREL_DNS = 0x4;
static const Shortint Id_IPREL_SSDP = 0x5;
static const Shortint Id_IPREL_DHCPV4 = 0x6;
static const Shortint Id_IPREL_AAP = 0x7;
static const Shortint Id_IPREL_MBUS = 0x8;

}	/* namespace Idassignednumbers */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Idassignednumbers;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdAssignedNumbers
