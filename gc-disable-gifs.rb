#!/usr/bin/env ruby

require 'uri'
require 'fileutils'
require 'open-uri'
require 'nokogiri'

def save_img(src, download_path)
  basename = File.basename(src)
  ext = File.extname(src)

  # long name fix
  if basename.length > 120
    basename = Digest::MD5.hexdigest(basename) + ext
  end

  # download and save image
  dest = "#{download_path}/#{basename}"
  if !File.file?(dest)
    puts "save #{src} to #{dest}"
    open(dest, 'wb') { |f| f <<  open(src).read }
  end
end

if __FILE__ == $0
  url = "https://github.com/composer/composer/commit/ac676f47f7bbc619678a29deae097b6b0710b799"
  img_xpath = ".//div[@class=\"comment-content\"]//.//img"
  download_path = "downloaded/ruby"

  # create download dir
  unless File.directory?(download_path)
    FileUtils.mkdir_p(download_path)
  end

  # load
  content = open(url) { |f| f.read }
  doc = Nokogiri::XML(content)
  doc.xpath(img_xpath).each do |e|
    # skip avatars
    unless e['src'].include? "avatars"
      save_img(e['src'], download_path)
    end
  end
end
