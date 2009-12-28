.for system in linux win32 sunos
	-rm -f classes/modules/${module}/bin/* 
	-cp tools/payments/HSBC/lib/${system}/* classes/modules/HSBC/bin
	@gnu_tar -cf ${module}_add-on-${VERSION}-${system}${asp}.tar ${FILES} --exclude=CVS --exclude=Makefile
.endfor
