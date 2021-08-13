import urllib.request
import os
import re

for file in os.listdir("/var/www/zettelkasten/zettel/kopton.org/"):
    name = os.path.splitext(file)[0]
    fp = urllib.request.urlopen(
        "http://localhost/static_view.php?link=" + name
    )
    mybytes = fp.read()

    mystr = mybytes.decode("utf8")
    fp.close()

    mystr = re.sub(
        r"href\=[\"\'](\/static_view\.php)?\?link=([a-z\_\.]+)[\"\']",
        "href='" + r"\2" + "'",
        mystr,
    )

    mystr = mystr.replace("<html>", "<html><meta charset='UTF-8'>")
    mystr = mystr.replace("index.html", "./")

    #    if name == "start":
    #        name = "index.html"
    fp = open("local_scripts/static_site_generator/site/" + name, "w")
    fp.write(mystr)
    fp.close()
