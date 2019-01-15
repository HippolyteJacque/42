using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class HeroMove : MonoBehaviour {

    private float movespeed = 250f;
    public GameObject legs;
    Animator m_Animator;
    private Rigidbody2D rb;
    private Vector2 moveVelocity;
    private GameObject pickedUp;

    private GameObject dropWeapon;
    private bool isDrop = false;
    private Vector3 target;
    public GameObject firePoint;
    public int roomID;

    public AudioClip getWeapon;
    private AudioSource source;

    public GameObject AmmoDisplay;

    void Start () {
        rb = GetComponent<Rigidbody2D>();
        source = GetComponent<AudioSource>();
    }

    void Update () {
        if (Input.GetKey("w") || Input.GetKey("s") || Input.GetKey("a") || Input.GetKey("d")){
           legs.GetComponent<Animator>().SetBool("isWalking", true);
        }
        else {
           legs.GetComponent<Animator>().SetBool("isWalking", false);
        }

        // DROP WEAPON
        if (Input.GetMouseButtonDown(1) && pickedUp){
            RaycastHit2D hit;
            target = Camera.main.ScreenToWorldPoint(Input.mousePosition);
            target.z = firePoint.transform.position.z;
            pickedUp.transform.position = firePoint.transform.position;
            hit = Physics2D.Raycast(pickedUp.transform.position, target - pickedUp.transform.position);
            Debug.DrawLine(pickedUp.transform.position, target, Color.white, 5f, false);
            if (hit != false) {
                if(hit.transform.tag == "wall"){
                    Debug.DrawLine(pickedUp.transform.position, hit.point, Color.red, 5f, false);
                    target = hit.point;
                }
            }
            pickedUp.SetActive(true);
            transform.Find(pickedUp.name).gameObject.SetActive(false);
            dropWeapon = pickedUp;
            isDrop = true;
            pickedUp = null;
            AmmoDisplay.GetComponent<Text>().text = "BULLIT";
        }

        if (isDrop == true){
            if (dropWeapon.transform.position != target){    
                float moveSpeed = 10f;
                dropWeapon.transform.position = Vector3.MoveTowards(dropWeapon.transform.position, target, moveSpeed * Time.deltaTime);
            }
            else {
                dropWeapon = null;
                isDrop = false;
            }
        }
        
    }

    void FixedUpdate(){
        

        if (Input.GetKey("w")){
            rb.AddForce(Vector2.up * movespeed);
        }
        if (Input.GetKey("s")){
            rb.AddForce(-Vector2.up * movespeed);
        }
        if (Input.GetKey("a")){
            rb.AddForce(-Vector2.right * movespeed);
        }
        if (Input.GetKey("d")){
            rb.AddForce(Vector2.right * movespeed);
        }
        rb.velocity = Vector2.zero;
        rb.angularVelocity = 0;
    }

    void OnTriggerStay2D(Collider2D col){
        // EQUIP WEAPON
        if (col.tag == "weapon" && Input.GetKey("e") && pickedUp == null){
            source.PlayOneShot(getWeapon);
            pickedUp = col.gameObject;
            pickedUp.SetActive(false);
            transform.Find(col.name).gameObject.SetActive(true);
        }
    }

    void OnTriggerEnter2D(Collider2D coll) {
        if (coll.tag == "room0") {
            roomID = 0;
        }
        else if (coll.tag == "room1") {
            roomID = 1; 
        }
        else if (coll.tag == "room2") {
            roomID = 2; 
        }
        else if (coll.tag == "room3") {
            roomID = 3; 
        }
        else if (coll.tag == "room4") {
            roomID = 4; 
        }
        else if (coll.tag == "room5") {
            roomID = 5; 
        }
    }

}