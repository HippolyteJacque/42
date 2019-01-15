using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class bulletShot : MonoBehaviour {

	public GameObject Projectile;
	[HideInInspector]public GameObject cloneProj;
	public float fireSpeed;
	public float fireRate;
	public Transform firePoint;
	public GameObject Shooter;
	public GameObject weapon;
	public float speed;
	public GameObject gm;

	public AudioClip gunShot;
	public AudioClip outOfAmmo;
	private AudioSource source;


	// Use this for initialization
	 void Start ()
 	{
 		source = GetComponent<AudioSource>();
     	transform.rotation = firePoint.transform.rotation;
     	Projectile.GetComponent<collideBullet>().gm = gm;
 		Projectile.GetComponent<collideBullet>().shooter = Shooter;
 	}
 
 
 	void Update ()
 	{
    	transform.rotation = firePoint.transform.rotation;
		
		if (gm.GetComponent<GameManager>().winOrLose == 0){
			if (Shooter.tag == "enemy") {
				Shooter.GetComponent<Enemy>().CheckDistance();
			}
			else {
				if (Input.GetKey(KeyCode.Mouse0) && Time.time > fireRate && weapon.GetComponent<Weapon>().ammo > 0)
	   			{
	       			fireRate = Time.time + fireSpeed;
	       			cloneProj = (GameObject)Instantiate(Projectile, firePoint.transform.position, transform.rotation);
					cloneProj.transform.Rotate(0, 0, -90);
					source.PlayOneShot(gunShot);
					if (Projectile.name == "bulletSaber") {	
						Destroy(cloneProj, 0.1f);
					}
					else if (Shooter.tag == "enemy") {
						cloneProj.GetComponent<Rigidbody2D>().velocity = -transform.up * speed;
					}
					else {
						weapon.GetComponent<Weapon>().ammo -= 1;
						cloneProj.GetComponent<Rigidbody2D>().velocity = -transform.up * speed;
					}	
	    		}
	    		else if (Input.GetKey(KeyCode.Mouse0) && Time.time > fireRate && weapon.GetComponent<Weapon>().ammo == 0){
	    			fireRate = Time.time + fireSpeed;
	    			source.PlayOneShot(outOfAmmo);
	    		}
			}
		}

	    
 	}
}
