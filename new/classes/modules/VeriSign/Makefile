.for system in linux win32 suni86pc sunsparc freebsd 
	-rm -f classes/modules/${module}/bin/* classes/modules/${module}/lib/*
	@echo Unpacking files from tools/payments/VeriSign/${system}.tgz to classes/modules/${module}
	@gnu_tar -zxvf tools/payments/VeriSign/${system}.tgz --unlink-first
	@gnu_tar -cf ${module}_add-on-${VERSION}-${system}${asp}.tar ${FILES} --exclude=CVS --exclude=Makefile
.endfor
