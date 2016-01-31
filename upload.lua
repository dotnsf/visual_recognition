print("HTTP/1.1 200 Internal OK\n\n")
last_fname = ""
last_fpath = ""
max_mod = 0
fpath = "/DCIM/100__TSB"
for filename in lfs.dir(fpath) do
  filepath = fpath .. "/" .. filename
  mod = lfs.attributes( filepath, "modification" )
  if mod > max_mod then
    max_mod = mod
    last_fname = filename
    last_fpath = filepath
  end
end
print(last_fpath)

boundary = "1234567890"
contenttype = "multipart/form-data; boundary=" .. boundary
mes = "--" ..  boundary .. "\r\n"
  .."Content-Disposition: form-data; name=\"file\"; filename=\""..last_fname.."\"\r\n"
  .."Content-Type: image/png\r\n\r\n"
  .."<!--WLANSDFILE-->\r\n"
  .."--" .. boundary .. "--\r\n"

blen = lfs.attributes(last_fpath,"size") + string.len(mes) - 17
b, c, h = fa.request{url = "http://XXXXXXXXXX.mybluemix.net/up.php",
  method = "POST",
  headers = {["Content-Length"] = tostring(blen),
  ["Content-Type"] = contenttype},
  file = last_fpath,
  body = mes
}

