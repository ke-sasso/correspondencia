
CREATE USER [solosistemas] FOR LOGIN [solosistemas] WITH DEFAULT_SCHEMA=[dbo]
GO
ALTER ROLE [db_owner] ADD MEMBER [MEDICAMENTOS\DesarrolloEventual]
GO
ALTER ROLE [db_datareader] ADD MEMBER [MEDICAMENTOS\kevin.sasso]
GO
ALTER ROLE [db_datawriter] ADD MEMBER [MEDICAMENTOS\kevin.sasso]
GO
ALTER ROLE [db_datareader] ADD MEMBER [solosistemas]
GO
ALTER ROLE [db_datawriter] ADD MEMBER [solosistemas]
GO
/****** Object:  Schema [COR]    Script Date: 21/10/2019 13:58:03 ******/
CREATE SCHEMA [COR]
GO
/****** Object:  Schema [SP]    Script Date: 21/10/2019 13:58:03 ******/
CREATE SCHEMA [SP]
GO
/****** Object:  Table [COR].[accion]    Script Date: 21/10/2019 13:58:03 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [COR].[accion](
	[idAccion] [int] IDENTITY(1,1) NOT NULL,
	[nombreAccion] [text] NULL,
	[usuarioCreacion] [varchar](50) NULL,
	[fechaCreacion] [datetime2](0) NULL,
	[usuarioModificacion] [varchar](50) NULL,
	[fechaModificacion] [datetime2](0) NULL,
PRIMARY KEY CLUSTERED 
(
	[idAccion] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [COR].[clasificacion]    Script Date: 21/10/2019 13:58:03 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [COR].[clasificacion](
	[idClasificacion] [int] IDENTITY(1,1) NOT NULL,
	[nombreClasificacion] [text] NULL,
	[usuarioCreacion] [varchar](50) NULL,
	[fechaCreacion] [datetime2](0) NULL,
	[usuarioModificacion] [varchar](50) NULL,
	[fechaModificacion] [datetime2](0) NULL,
PRIMARY KEY CLUSTERED 
(
	[idClasificacion] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [COR].[comentarioDestino]    Script Date: 21/10/2019 13:58:03 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [COR].[comentarioDestino](
	[idDestino] [int] IDENTITY(1,1) NOT NULL,
	[idComentario] [int] NULL,
	[idParticipante] [int] NULL,
	[idPadre] [int] NULL,
	[idSolicitud] [int] NULL,
	[usuarioCreacion] [varchar](50) NULL,
	[fechaCreacion] [datetime2](0) NULL,
	[usuarioModificacion] [varchar](50) NULL,
	[fechaModificacion] [datetime2](0) NULL,
PRIMARY KEY CLUSTERED 
(
	[idDestino] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [COR].[denunciaDetalle]    Script Date: 21/10/2019 13:58:03 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [COR].[denunciaDetalle](
	[idDetalle] [int] IDENTITY(1,1) NOT NULL,
	[idDenuncia] [int] NULL,
	[idRegistro] [int] NULL,
PRIMARY KEY CLUSTERED 
(
	[idDetalle] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
/****** Object:  Table [COR].[denunciaRegistro]    Script Date: 21/10/2019 13:58:03 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [COR].[denunciaRegistro](
	[idRegistro] [int] IDENTITY(1,1) NOT NULL,
	[nombreComercial] [nvarchar](500) NULL,
	[noRegistro] [nvarchar](50) NULL,
	[titular] [nvarchar](150) NULL,
	[fechaVencimiento] [date] NULL,
	[noLote] [nvarchar](100) NULL,
	[tipoRegistro] [int] NULL,
	[direccion] [text] NULL,
	[observacion] [text] NULL,
	[estado] [varchar](10) NULL,
	[usuarioCreacion] [varchar](50) NULL,
	[fechaCreacion] [datetime2](0) NULL,
	[usuarioModificacion] [varchar](50) NULL,
	[fechaModificacion] [datetime2](0) NULL,
PRIMARY KEY CLUSTERED 
(
	[idRegistro] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [COR].[estadoSolicitud]    Script Date: 21/10/2019 13:58:03 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [COR].[estadoSolicitud](
	[idEstado] [int] NOT NULL,
	[nombreEstado] [varchar](50) NOT NULL,
	[usuarioCreacion] [varchar](50) NULL,
	[fechaCreacion] [datetime2](0) NULL,
	[usuarioModificacion] [varchar](50) NULL,
	[fechaModificacion] [datetime2](0) NULL,
 CONSTRAINT [PK__estadoSo__62EA894ABF1F8B14] PRIMARY KEY CLUSTERED 
(
	[idEstado] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [COR].[fechaRespuesta]    Script Date: 21/10/2019 13:58:03 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [COR].[fechaRespuesta](
	[idfechaRespuesta] [int] IDENTITY(1,1) NOT NULL,
	[nombreFecha] [text] NULL,
	[usuarioCreacion] [varchar](50) NULL,
	[fechaCreacion] [datetime2](0) NULL,
	[usuarioModificacion] [varchar](50) NULL,
	[fechaModificacion] [datetime2](0) NULL,
PRIMARY KEY CLUSTERED 
(
	[idfechaRespuesta] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [COR].[justificacionesProrroga]    Script Date: 21/10/2019 13:58:03 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [COR].[justificacionesProrroga](
	[idItem] [int] IDENTITY(1,1) NOT NULL,
	[idSolicitud] [int] NULL,
	[justificacion] [text] NULL,
	[dias] [int] NULL,
	[usuarioCreacion] [varchar](50) NULL,
	[fechaCreacion] [datetime2](0) NOT NULL,
	[usuarioModificacion] [varchar](50) NULL,
	[fechaModificacion] [datetime2](0) NULL,
PRIMARY KEY CLUSTERED 
(
	[idItem] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [COR].[mediosRecepcion]    Script Date: 21/10/2019 13:58:03 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [COR].[mediosRecepcion](
	[idMedio] [int] IDENTITY(1,1) NOT NULL,
	[nombreMedio] [nvarchar](100) NULL,
	[usuarioCreacion] [varchar](50) NULL,
	[fechaCreacion] [datetime2](0) NULL,
	[usuarioModificacion] [varchar](50) NULL,
	[fechaModificacion] [datetime2](0) NULL,
PRIMARY KEY CLUSTERED 
(
	[idMedio] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [COR].[participantes]    Script Date: 21/10/2019 13:58:03 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [COR].[participantes](
	[idPersonaParticipante] [int] IDENTITY(1,1) NOT NULL,
	[idEmpleado] [int] NOT NULL,
	[idEmpleadoAsistente] [int] NULL,
	[estado] [int] NULL,
PRIMARY KEY CLUSTERED 
(
	[idPersonaParticipante] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
/****** Object:  Table [COR].[solicitudAdjunto]    Script Date: 21/10/2019 13:58:03 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [COR].[solicitudAdjunto](
	[idAdjunto] [int] IDENTITY(1,1) NOT NULL,
	[idSolicitud] [int] NOT NULL,
	[urlArchivo] [text] NOT NULL,
	[tipoArchivo] [nchar](100) NULL,
	[usuarioCreacion] [varchar](50) NULL,
	[fechaCreacion] [datetime2](0) NOT NULL,
	[usuarioModificacion] [varchar](50) NULL,
	[fechaModificacion] [datetime2](0) NULL,
	[nombreArchivo] [text] NULL,
	[idEstado] [int] NULL,
PRIMARY KEY CLUSTERED 
(
	[idAdjunto] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [COR].[solicitudComentarios]    Script Date: 21/10/2019 13:58:03 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [COR].[solicitudComentarios](
	[idComentario] [int] IDENTITY(1,1) NOT NULL,
	[comentario] [text] NULL,
	[archivo] [text] NULL,
	[tipoArchivo] [nchar](100) NULL,
	[idSolicitud] [int] NOT NULL,
	[idParticipante] [int] NOT NULL,
	[usuarioCreacion] [varchar](50) NULL,
	[fechaCreacion] [datetime2](0) NOT NULL,
	[usuarioModificacion] [varchar](50) NULL,
	[fechaModificacion] [datetime2](0) NULL,
	[idEstado] [int] NULL,
	[tipoComentario] [int] NULL,
	[idPadre] [int] NULL,
	[nombreArchivo] [nvarchar](200) NULL,
 CONSTRAINT [PK__solicitu__C74515DAE66DEE33] PRIMARY KEY CLUSTERED 
(
	[idComentario] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [COR].[solicitudDenuncia]    Script Date: 21/10/2019 13:58:03 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [COR].[solicitudDenuncia](
	[idDenuncia] [int] NOT NULL,
	[nombreUsuario] [varchar](100) NULL,
	[telLlamada] [nchar](9) NULL,
	[idDepartamento] [int] NULL,
	[idMunicipio] [int] NULL,
	[edad] [nchar](2) NULL,
	[profesion] [nvarchar](100) NULL,
	[tipoDoc] [nvarchar](60) NULL,
	[noDocumento] [nvarchar](31) NULL,
	[ofrecePrueba] [text] NULL,
	[pide] [text] NULL,
	[fechaEvento] [date] NULL,
	[tel1Notificar] [nchar](9) NULL,
	[tel2Notificar] [nchar](9) NULL,
	[correo] [nvarchar](100) NULL,
	[aviso] [nvarchar](500) NULL,
	[operador] [varchar](100) NULL,
	[observacion] [text] NULL,
	[usuarioCreacion] [varchar](50) NULL,
	[fechaCreacion] [datetime2](0) NULL,
	[usuarioModificacion] [varchar](50) NULL,
	[fechaModificacion] [datetime2](0) NULL,
	[comentarioPDF] [text] NULL,
PRIMARY KEY CLUSTERED 
(
	[idDenuncia] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [COR].[solicitudes]    Script Date: 21/10/2019 13:58:03 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [COR].[solicitudes](
	[idSolicitud] [int] NOT NULL,
	[nitSolicitante] [nchar](17) NULL,
	[fechaRecepcion] [datetime2](0) NOT NULL,
	[fechaRespuesta] [datetime2](0) NULL,
	[asunto] [nvarchar](300) NULL,
	[descripcion] [nvarchar](max) NULL,
	[noPresentacion] [nchar](15) NULL,
	[idEstado] [int] NOT NULL,
	[usuarioCreacion] [varchar](50) NULL,
	[fechaCreacion] [datetime2](0) NOT NULL,
	[usuarioModificacion] [varchar](50) NULL,
	[fechaModificacion] [datetime2](0) NULL,
	[idClasificacion] [int] NULL,
	[idfechaRespuesta] [int] NULL,
	[comentario] [nvarchar](700) NULL,
	[dias] [int] NULL,
	[usuarioDetalle] [varchar](50) NULL,
	[fechaDetalle] [datetime2](0) NULL,
	[idTipo] [nchar](1) NULL,
	[usuarioEnviarJunta] [varchar](50) NULL,
	[observaciones] [text] NULL,
	[idMedio] [int] NULL,
	[fechaFinalProceso] [date] NULL,
	[correoNotificar] [varchar](100) NULL,
 CONSTRAINT [PK__solicitu__D801DDB889D57FE5] PRIMARY KEY CLUSTERED 
(
	[idSolicitud] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [COR].[solicitudesAcciones]    Script Date: 21/10/2019 13:58:03 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [COR].[solicitudesAcciones](
	[idsolicitudAcciones] [int] IDENTITY(1,1) NOT NULL,
	[idSolicitud] [int] NULL,
	[idAccion] [int] NULL,
	[usuarioCreacion] [varchar](50) NULL,
	[fechaCreacion] [datetime2](0) NULL,
	[usuarioModificacion] [varchar](50) NULL,
	[fechaModificacion] [datetime2](0) NULL,
PRIMARY KEY CLUSTERED 
(
	[idsolicitudAcciones] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [COR].[solicitudHistorial]    Script Date: 21/10/2019 13:58:03 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [COR].[solicitudHistorial](
	[idHistorial] [int] IDENTITY(1,1) NOT NULL,
	[idSolicitud] [int] NOT NULL,
	[idEstado] [int] NOT NULL,
	[observacion] [text] NULL,
	[usuarioCreacion] [varchar](50) NULL,
	[fechaCreacion] [datetime2](0) NOT NULL,
	[usuarioModificacion] [varchar](50) NULL,
	[fechaModificacion] [datetime2](0) NULL,
PRIMARY KEY CLUSTERED 
(
	[idHistorial] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [COR].[solicitudParticipantes]    Script Date: 21/10/2019 13:58:03 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [COR].[solicitudParticipantes](
	[idParticipante] [int] IDENTITY(1,1) NOT NULL,
	[idSolicitud] [int] NOT NULL,
	[idEmpleado] [int] NOT NULL,
	[usuarioCreacion] [varchar](50) NULL,
	[fechaCreacion] [datetime2](0) NULL,
	[usuarioModificacion] [varchar](50) NULL,
	[fechaModificacion] [datetime2](0) NULL,
	[fechaRespuesta] [date] NULL,
	[idEstado] [int] NULL,
	[caso] [int] NULL,
	[permiso] [int] NULL,
 CONSTRAINT [PK__solicitu__DDB5CB96B7DAC1C4] PRIMARY KEY CLUSTERED 
(
	[idParticipante] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [COR].[solicitudSeguimiento]    Script Date: 21/10/2019 13:58:03 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [COR].[solicitudSeguimiento](
	[idRequest] [int] IDENTITY(1,1) NOT NULL,
	[idSolicitud] [int] NOT NULL,
	[estadoSolicitud] [int] NOT NULL,
	[observaciones] [text] NOT NULL,
	[fechaCreacion] [datetime2](0) NOT NULL,
	[idUsuarioCreacion] [text] NOT NULL,
 CONSTRAINT [PK_solicitudSeguimiento] PRIMARY KEY CLUSTERED 
(
	[idRequest] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]

GO
/****** Object:  Table [COR].[solicitudTitulares]    Script Date: 21/10/2019 13:58:03 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [COR].[solicitudTitulares](
	[idSolicitudTit] [int] IDENTITY(1,1) NOT NULL,
	[idSolicitud] [int] NULL,
	[idTitular] [int] NULL,
	[usuarioCreacion] [varchar](50) NULL,
	[fechaCreacion] [datetime2](0) NOT NULL,
	[usuarioModificacion] [varchar](50) NULL,
	[fechaModificacion] [datetime2](0) NULL,
PRIMARY KEY CLUSTERED 
(
	[idSolicitudTit] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [COR].[tipoParticipantes]    Script Date: 21/10/2019 13:58:03 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [COR].[tipoParticipantes](
	[idTipoParticipante] [int] IDENTITY(1,1) NOT NULL,
	[nombreTipoParticipante] [nvarchar](50) NULL,
PRIMARY KEY CLUSTERED 
(
	[idTipoParticipante] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
/****** Object:  Table [COR].[titulares]    Script Date: 21/10/2019 13:58:03 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [COR].[titulares](
	[idTitular] [int] IDENTITY(1,1) NOT NULL,
	[nombreTitular] [varchar](255) NOT NULL,
	[telefonosContacto] [text] NULL,
	[emailContacto] [text] NULL,
	[usuarioCreacion] [varchar](50) NULL,
	[fechaCreacion] [datetime2](0) NOT NULL,
	[usuarioModificacion] [varchar](50) NULL,
	[fechaModificacion] [datetime2](0) NULL,
PRIMARY KEY CLUSTERED 
(
	[idTitular] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [COR].[usuarioEntregado]    Script Date: 21/10/2019 13:58:03 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [COR].[usuarioEntregado](
	[idEntregado] [int] IDENTITY(1,1) NOT NULL,
	[idSolicitud] [int] NULL,
	[nombres] [varchar](100) NULL,
	[apellidos] [varchar](100) NULL,
	[email] [varchar](100) NULL,
	[telefono] [varchar](60) NULL,
	[observacion] [text] NULL,
	[usuarioCreacion] [varchar](50) NULL,
	[fechaCreacion] [datetime2](0) NULL,
	[usuarioModificacion] [varchar](50) NULL,
	[fechaModificacion] [datetime2](0) NULL,
	[nit] [varchar](50) NULL,
	[dui] [varchar](50) NULL,
	[fechaNacimiento] [date] NULL,
	[contra] [varchar](max) NULL,
 CONSTRAINT [PK__usuarioE__692FD284AC5B7DC5] PRIMARY KEY CLUSTERED 
(
	[idEntregado] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  View [COR].[vwSolicitudes]    Script Date: 21/10/2019 13:58:03 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
create view  [COR].[vwSolicitudes] as 
SELECT        T1.idSolicitud, T1.nitSolicitante, T1.fechaRecepcion, T1.asunto, T1.descripcion, T1.noPresentacion, T1.idEstado, T1.idClasificacion, T1.idfechaRespuesta, T1.dias, T1.fechaDetalle, T1.fechaCreacion, T1.idTipo, T3.nombreEstado, 
                         T1.fechaRespuesta
FROM            COR.solicitudes AS T1 INNER JOIN
                         COR.estadoSolicitud AS T3 ON T1.idEstado = T3.idEstado;

GO
/****** Object:  View [COR].[vwSolicitudesEnProceso]    Script Date: 21/10/2019 13:58:03 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [COR].[vwSolicitudesEnProceso]
as
SELECT 
	vw.idSolicitud,
	vw.noPresentacion,
	vw.asunto,
	isnull(vw.dias,2) as diasRespuesta,
	DATEDIFF(DAY,vw.fechaDetalle,GETDATE()) as diasEnProceso,
	vw.fechaCreacion,
	vw.fechaDetalle as fechaAsignada,
	vw.nombreEstado,
	(emp.nombresEmpleado + ' ' + emp.apellidosEmpleado) as nombreEmpleado,
	usuarios.correo,
	emp.idEmpleado
  FROM [dnm_correspondencia_si].[COR].[vwSolicitudes] vw
  inner join dnm_correspondencia_si.COR.solicitudParticipantes par on par.idSolicitud = vw.idSolicitud
  inner join dnm_rrhh_si.RH.empleados emp on emp.idEmpleado = par.idEmpleado
  left join (Select * from OPENQUERY(PROD_1015,'Select idEmpleado,trim(correo) as correo from dnm_catalogos.sys_usuarios')) as usuarios on usuarios.idEmpleado = emp.idEmpleado
  WHERE vw.idEstado in (2,3) and par.idEstado = 1

GO
ALTER TABLE [COR].[comentarioDestino]  WITH CHECK ADD  CONSTRAINT [fk_idComentarioDestino] FOREIGN KEY([idComentario])
REFERENCES [COR].[solicitudComentarios] ([idComentario])
ON DELETE CASCADE
GO
ALTER TABLE [COR].[comentarioDestino] CHECK CONSTRAINT [fk_idComentarioDestino]
GO
ALTER TABLE [COR].[comentarioDestino]  WITH CHECK ADD  CONSTRAINT [fk_partDestino] FOREIGN KEY([idParticipante])
REFERENCES [COR].[solicitudParticipantes] ([idParticipante])
ON DELETE CASCADE
GO
ALTER TABLE [COR].[comentarioDestino] CHECK CONSTRAINT [fk_partDestino]
GO
ALTER TABLE [COR].[justificacionesProrroga]  WITH CHECK ADD  CONSTRAINT [FK_idSolicitud_justificaciones] FOREIGN KEY([idSolicitud])
REFERENCES [COR].[solicitudes] ([idSolicitud])
GO
ALTER TABLE [COR].[justificacionesProrroga] CHECK CONSTRAINT [FK_idSolicitud_justificaciones]
GO
ALTER TABLE [COR].[solicitudAdjunto]  WITH CHECK ADD  CONSTRAINT [FK_idSolicitud_adjunto] FOREIGN KEY([idSolicitud])
REFERENCES [COR].[solicitudes] ([idSolicitud])
ON DELETE CASCADE
GO
ALTER TABLE [COR].[solicitudAdjunto] CHECK CONSTRAINT [FK_idSolicitud_adjunto]
GO
ALTER TABLE [COR].[solicitudComentarios]  WITH CHECK ADD  CONSTRAINT [FK_idParticipante] FOREIGN KEY([idParticipante])
REFERENCES [COR].[solicitudParticipantes] ([idParticipante])
GO
ALTER TABLE [COR].[solicitudComentarios] CHECK CONSTRAINT [FK_idParticipante]
GO
ALTER TABLE [COR].[solicitudComentarios]  WITH CHECK ADD  CONSTRAINT [FK_idSolicitud_comentario] FOREIGN KEY([idSolicitud])
REFERENCES [COR].[solicitudes] ([idSolicitud])
GO
ALTER TABLE [COR].[solicitudComentarios] CHECK CONSTRAINT [FK_idSolicitud_comentario]
GO
ALTER TABLE [COR].[solicitudes]  WITH CHECK ADD  CONSTRAINT [fk_idClasificacion] FOREIGN KEY([idClasificacion])
REFERENCES [COR].[clasificacion] ([idClasificacion])
GO
ALTER TABLE [COR].[solicitudes] CHECK CONSTRAINT [fk_idClasificacion]
GO
ALTER TABLE [COR].[solicitudes]  WITH CHECK ADD  CONSTRAINT [fk_idFechaRespuesta] FOREIGN KEY([idfechaRespuesta])
REFERENCES [COR].[fechaRespuesta] ([idfechaRespuesta])
GO
ALTER TABLE [COR].[solicitudes] CHECK CONSTRAINT [fk_idFechaRespuesta]
GO
ALTER TABLE [COR].[solicitudes]  WITH CHECK ADD  CONSTRAINT [FK_idMedio] FOREIGN KEY([idMedio])
REFERENCES [COR].[mediosRecepcion] ([idMedio])
GO
ALTER TABLE [COR].[solicitudes] CHECK CONSTRAINT [FK_idMedio]
GO
ALTER TABLE [COR].[solicitudes]  WITH CHECK ADD  CONSTRAINT [FK_tipoEstado_solicitud] FOREIGN KEY([idEstado])
REFERENCES [COR].[estadoSolicitud] ([idEstado])
GO
ALTER TABLE [COR].[solicitudes] CHECK CONSTRAINT [FK_tipoEstado_solicitud]
GO
ALTER TABLE [COR].[solicitudesAcciones]  WITH CHECK ADD  CONSTRAINT [fk_idAccion] FOREIGN KEY([idAccion])
REFERENCES [COR].[accion] ([idAccion])
GO
ALTER TABLE [COR].[solicitudesAcciones] CHECK CONSTRAINT [fk_idAccion]
GO
ALTER TABLE [COR].[solicitudesAcciones]  WITH CHECK ADD  CONSTRAINT [fk_idSolicitudAcciones] FOREIGN KEY([idSolicitud])
REFERENCES [COR].[solicitudes] ([idSolicitud])
GO
ALTER TABLE [COR].[solicitudesAcciones] CHECK CONSTRAINT [fk_idSolicitudAcciones]
GO
ALTER TABLE [COR].[solicitudHistorial]  WITH CHECK ADD  CONSTRAINT [FK_idEstado_historial] FOREIGN KEY([idEstado])
REFERENCES [COR].[estadoSolicitud] ([idEstado])
GO
ALTER TABLE [COR].[solicitudHistorial] CHECK CONSTRAINT [FK_idEstado_historial]
GO
ALTER TABLE [COR].[solicitudHistorial]  WITH CHECK ADD  CONSTRAINT [FK_idSolicitud_historial] FOREIGN KEY([idSolicitud])
REFERENCES [COR].[solicitudes] ([idSolicitud])
ON DELETE CASCADE
GO
ALTER TABLE [COR].[solicitudHistorial] CHECK CONSTRAINT [FK_idSolicitud_historial]
GO
ALTER TABLE [COR].[solicitudParticipantes]  WITH CHECK ADD  CONSTRAINT [FK_idSolicitud_participantes] FOREIGN KEY([idSolicitud])
REFERENCES [COR].[solicitudes] ([idSolicitud])
ON DELETE CASCADE
GO
ALTER TABLE [COR].[solicitudParticipantes] CHECK CONSTRAINT [FK_idSolicitud_participantes]
GO
ALTER TABLE [COR].[solicitudTitulares]  WITH CHECK ADD  CONSTRAINT [FK_idSolcitud_solTit] FOREIGN KEY([idSolicitud])
REFERENCES [COR].[solicitudes] ([idSolicitud])
GO
ALTER TABLE [COR].[solicitudTitulares] CHECK CONSTRAINT [FK_idSolcitud_solTit]
GO
ALTER TABLE [COR].[solicitudTitulares]  WITH CHECK ADD  CONSTRAINT [FK_idtitular_soltit] FOREIGN KEY([idTitular])
REFERENCES [COR].[titulares] ([idTitular])
GO
ALTER TABLE [COR].[solicitudTitulares] CHECK CONSTRAINT [FK_idtitular_soltit]
GO
/****** Object:  StoredProcedure [SP].[insert_prueba2]    Script Date: 21/10/2019 13:58:03 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
create procedure [SP].[insert_prueba2]
@idSolicitud int,@nombres varchar(100),@apellidos varchar(100),@correo varchar(100),
@telefono varchar(10), @observacion text,@usuarioCreacion varchar(100),@fechaNacimiento date
AS
INSERT INTO [dnm_correspondencia_si].[COR].[usuarioEntregado] 
(idSolicitud,nombres,apellidos,email,telefono,observacion,usuarioCreacion,fechaCreacion,fechaNacimiento)
VALUES (@idSolicitud,@nombres,@apellidos,@correo,@telefono,@observacion,@usuarioCreacion,GETDATE(),@fechaNacimiento)

GO
/****** Object:  StoredProcedure [SP].[SP_PARAMETROS]    Script Date: 21/10/2019 13:58:03 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [SP].[SP_PARAMETROS] @Parametros varchar(MAX)
--@Parametros es la cadena de entrada
AS

SET NOCOUNT ON
--El separador de nuestros parametros sera una ,
DECLARE @Posicion int
--@Posicion es la posicion de cada uno de nuestros separadores
DECLARE @Parametro varchar(1000)
--@Parametro es cada uno de los valores obtenidos
--que almacenaremos en #parametros
SET @Parametros = @Parametros + ','
--Colocamos un separador al final de los parametros
--para que funcione bien nuestro codigo
--Hacemos un bucle que se repite mientras haya separadores
WHILE patindex('%,%' , @Parametros) <> 0
--patindex busca un patron en una cadena y nos devuelve su posicion
BEGIN
  SELECT @Posicion =  patindex('%,%' , @Parametros)
  --Buscamos la posicion de la primera ,
  SELECT @Parametro = left(@Parametros, @Posicion - 1)
  --Y cogemos los caracteres hasta esa posicion
  UPDATE  [dnm_correspondencia_si].[COR].[usuarioEntregado] SET CONTRA=3  where idEntregado=@Parametro;
  --y ese parámetro lo guardamos en la tabla temporal
  --Reemplazamos lo procesado con nada con la funcion stuff
  SELECT @Parametros = stuff(@Parametros, 1, @Posicion, '')
END
SET NOCOUNT OFF

GO
