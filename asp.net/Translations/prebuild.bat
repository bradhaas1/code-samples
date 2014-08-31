Echo Off
SET SPLANGEXT=cs

Echo Backing up previous version of generated code ... 
IF NOT EXIST .\PreviousVersionGeneratedCode MkDir .\PreviousVersionGeneratedCode
IF EXIST ProjectManagement.%SPLANGEXT% xcopy /Y/V ProjectManagement.%SPLANGEXT% .\PreviousVersionGeneratedCode

Echo Generating code ...
"C:\Program Files\Common Files\Microsoft Shared\Web Server Extensions\14\BIN\SPMetal.exe" /web:http://2-5-2013-diono/project-tracking /code:ProjectManagement.%SPLANGEXT%
